@extends('sekretaris.layouts.master')

@section('title', 'Catatan Arisan')

@section('content')

<div class="roda-spin-wrapper">
    <div class="roda-center-container">
        <h1 class="roda-wheel-title">🎡 Spin Arisan</h1>
        <div id="roda-chart">
            <div id="roda-arrow"></div>
            <div id="roda-spin-button">PUTAR</div>
        </div>

        <div id="roda-input-names">
            <div class="roda-form-group">
                <label class="roda-form-label" for="roda-anggota-select">Pilih Anggota Arisan</label>
                <select id="roda-anggota-select" class="roda-form-select">
                    <option value="">-- Pilih Anggota --</option>
                    @foreach($anggota as $user)
                    <option value="{{ $user->id }}" data-name="{{ $user->name }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="button" id="roda-add-member" class="roda-btn roda-btn-add">
                ➕ Tambah ke Roda
            </button>


            <button type="button" id="roda-generate-wheel" class="roda-btn roda-btn-primary" disabled>
                ✨ Buat Roda
            </button>

            <button type="button" id="roda-reset-names" class="roda-btn roda-btn-secondary">
                🔄 Reset Semua
            </button>
        </div>
    </div>
</div>

<div id="roda-winner-display">
    <div class="roda-winner-content">
        <span class="roda-winner-emoji">🎉</span>
        <h1 id="roda-winner-name"></h1>
        <button id="roda-close-winner" class="roda-btn-close">Tutup</button>
    </div>
</div>

{{-- Data spin: @json tidak akan dirusak editor karena ini Blade directive seperti @foreach --}}
<div id="spin-config" hidden
    data-members='@json($savedMembers ?? [])'
    data-save-url="{{ route('sekretaris.spin.save') }}"
    data-csrf="{{ csrf_token() }}"></div>

@endsection

@push('scripts')
<script src="https://d3js.org/d3.v3.min.js"></script>
<script>
    (function() {
        // Data dari server — dibaca dari data-attribute HTML, tidak bisa dirusak editor
        const _cfg = document.getElementById('spin-config').dataset;
        var savedSpinMembers = JSON.parse(_cfg.members || '[]');
        var spinSaveUrl = _cfg.saveUrl;
        var csrfToken = _cfg.csrf;

        let rodaSvg, rodaContainer, rodaVis, rodaPie, rodaArc, rodaOldRotation = 0,
            rodaRotation = 0,
            rodaPicked = 0;
        const rodaColor = d3.scale.category20();
        let selectedMembers = [];

        // =========================================
        // INISIALISASI: Load data dari server dulu
        // =========================================
        document.addEventListener('DOMContentLoaded', function() {
            if (savedSpinMembers && savedSpinMembers.length > 0) {
                selectedMembers = savedSpinMembers;
            }

            updateSelectedMembersList();
            updateSelectOptions();

            if (selectedMembers.length > 0) {
                createRodaWheel(selectedMembers.map(m => m.name));
            }
        });

        // =========================================
        // SIMPAN KE DATABASE
        // =========================================
        function saveToDb() {
            fetch(spinSaveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    members: selectedMembers
                })
            }).catch(function(err) {
                console.error('Gagal menyimpan spin members:', err);
            });
        }

        // =========================================
        // UPDATE TAMPILAN DAFTAR PESERTA
        // =========================================
        function updateSelectedMembersList() {
            const container = document.getElementById('roda-selected-members');
            if (container) {
                if (selectedMembers.length === 0) {
                    container.innerHTML = '<span style="color: #a0aec0; font-size: 14px;">Belum ada peserta</span>';
                } else {
                    container.innerHTML = selectedMembers.map(member =>
                        `<span class="roda-selected-item">
                        ${member.name}
                        <span class="roda-remove-item" onclick="removeMember('${member.id}')">✕</span>
                    </span>`
                    ).join('');
                }
            }

            const btn = document.getElementById('roda-generate-wheel');
            if (btn) btn.disabled = selectedMembers.length === 0;
        }

        function updateSelectOptions() {
            const selectElement = document.getElementById('roda-anggota-select');
            const selectedIds = selectedMembers.map(m => String(m.id));
            selectElement.value = "";
            Array.from(selectElement.options).forEach(option => {
                if (option.value && selectedIds.includes(String(option.value))) {
                    option.style.display = 'none';
                } else {
                    option.style.display = 'block';
                }
            });
        }

        // =========================================
        // TAMBAH ANGGOTA
        // =========================================
        document.getElementById('roda-add-member').addEventListener('click', function() {
            const selectElement = document.getElementById('roda-anggota-select');
            const selectedOption = selectElement.options[selectElement.selectedIndex];

            if (!selectedOption.value) {
                alert("⚠️ Pilih anggota terlebih dahulu!");
                return;
            }

            const memberId = selectedOption.value;
            const memberName = selectedOption.getAttribute('data-name');

            if (selectedMembers.some(m => String(m.id) === String(memberId))) {
                alert("⚠️ Anggota sudah ditambahkan!");
                return;
            }

            selectedMembers.push({
                id: memberId,
                name: memberName
            });
            saveToDb();
            updateSelectedMembersList();
            updateSelectOptions();
        });

        // =========================================
        // HAPUS ANGGOTA
        // =========================================
        window.removeMember = function(memberId) {
            selectedMembers = selectedMembers.filter(m => String(m.id) !== String(memberId));
            saveToDb();
            updateSelectedMembersList();
            updateSelectOptions();

            if (rodaSvg && selectedMembers.length > 0) {
                createRodaWheel(selectedMembers.map(m => m.name));
            } else if (selectedMembers.length === 0 && rodaSvg) {
                rodaSvg.remove();
                rodaSvg = null;
            }
        };

        // =========================================
        // BUAT RODA
        // =========================================
        function createRodaWheel(names) {
            if (rodaSvg) {
                rodaSvg.remove();
                rodaOldRotation = 0;
                rodaRotation = 0;
            }

            const data = names.map((name, index) => ({
                label: name,
                value: index + 1
            }));
            const w = 500;
            const h = 500;
            const r = Math.min(w, h) / 2;

            rodaSvg = d3.select('#roda-chart')
                .append("svg")
                .data([data])
                .attr("viewBox", `0 0 ${w} ${h}`)
                .attr("preserveAspectRatio", "xMidYMid meet");

            rodaContainer = rodaSvg.append("g")
                .attr("class", "roda-chartholder")
                .attr("transform", `translate(${w / 2}, ${h / 2})`);

            rodaVis = rodaContainer.append("g");
            rodaPie = d3.layout.pie().sort(null).value(() => 1);
            rodaArc = d3.svg.arc().outerRadius(r);

            const arcs = rodaVis.selectAll("g.roda-slice")
                .data(rodaPie)
                .enter()
                .append("g")
                .attr("class", "roda-slice");

            arcs.append("path")
                .attr("fill", (d, i) => rodaColor(i))
                .attr("d", rodaArc)
                .style("stroke", "#fff")
                .style("stroke-width", "3px");

            arcs.append("text")
                .attr("transform", function(d) {
                    d.innerRadius = 0;
                    d.outerRadius = r;
                    d.angle = (d.startAngle + d.endAngle) / 2;
                    return `rotate(${(d.angle * 180 / Math.PI - 90)})translate(${d.outerRadius - 10})`;
                })
                .attr("text-anchor", "end")
                .style("font-size", "20px")
                .style("font-weight", "bold")
                .style("fill", "#fff")
                .style("text-shadow", "2px 2px 4px rgba(0,0,0,0.5)")
                .text((d, i) => data[i].label);

            document.getElementById('roda-spin-button').addEventListener('click', spinRoda);

            function spinRoda() {
                document.getElementById('roda-spin-button').removeEventListener('click', spinRoda);

                const ps = 360 / data.length;
                // 15-20 putaran penuh agar terasa dramatis sebelum melambat
                const fullSpins = Math.floor(Math.random() * 6) + 15;
                const extraDeg = Math.floor(Math.random() * data.length) * ps;
                rodaRotation = (fullSpins * 360) + extraDeg;

                // Tentukan pemenang dari posisi akhir
                const finalAngle = rodaRotation % 360;
                rodaPicked = Math.round(data.length - finalAngle / ps);
                rodaPicked = rodaPicked >= data.length ? (rodaPicked % data.length) : rodaPicked;

                rodaRotation += 90 - Math.round(ps / 2);

                const startAngle = rodaOldRotation;
                const endAngle = rodaRotation;

                rodaVis.transition()
                    .duration(7000)
                    .ease('cubic-out')
                    .attrTween("transform", function() {
                        return function(t) {
                            // t sudah di-ease oleh D3 cubic-out, cukup interpolasi linear
                            rodaOldRotation = startAngle + (endAngle - startAngle) * t;
                            return `rotate(${rodaOldRotation})`;
                        };
                    })
                    .each("end", function() {
                        const winnerName = data[rodaPicked].label;

                        selectedMembers = selectedMembers.filter(m => m.name !== winnerName);
                        saveToDb();

                        displayRodaWinner(winnerName);

                        setTimeout(() => {
                            updateSelectedMembersList();
                            updateSelectOptions();

                            if (selectedMembers.length > 0) {
                                createRodaWheel(selectedMembers.map(m => m.name));
                            } else {
                                alert("🎊 Semua peserta sudah mendapat giliran!");
                            }
                        }, 800);
                    });
            }

        }

        function displayRodaWinner(name) {
            const winnerDisplay = document.getElementById('roda-winner-display');
            const winnerName = document.getElementById('roda-winner-name');
            winnerName.textContent = `Selamat ${name}!`;
            winnerDisplay.style.display = 'block';

            for (let i = 0; i < 150; i++) {
                setTimeout(() => createRodaConfetti(), i * 30);
            }
        }

        function createRodaConfetti() {
            const confetti = document.createElement('div');
            confetti.className = 'roda-confetti';
            confetti.style.left = `${Math.random() * 100}vw`;
            confetti.style.top = `-10px`;
            confetti.style.backgroundColor = `hsl(${Math.random() * 360}, 100%, 50%)`;
            confetti.style.animationDuration = `${Math.random() * 3 + 2}s`;
            confetti.style.width = `${Math.random() * 10 + 5}px`;
            confetti.style.height = confetti.style.width;
            document.body.appendChild(confetti);
            setTimeout(() => confetti.remove(), 5000);
        }

        document.getElementById('roda-generate-wheel').addEventListener('click', () => {
            if (selectedMembers.length === 0) {
                alert("⚠️ Tambahkan minimal satu anggota!");
                return;
            }
            createRodaWheel(selectedMembers.map(m => m.name));
        });

        document.getElementById('roda-reset-names').addEventListener('click', () => {
            if (confirm("Yakin ingin mereset semua data?")) {
                selectedMembers = [];
                saveToDb();
                updateSelectedMembersList();
                updateSelectOptions();
                if (rodaSvg) {
                    rodaSvg.remove();
                    rodaSvg = null;
                }
            }
        });

        document.getElementById('roda-close-winner').addEventListener('click', () => {
            document.getElementById('roda-winner-display').style.display = 'none';
        });

    })();
</script>
@endpush