
        let rodaSvg, rodaContainer, rodaVis, rodaPie, rodaArc, rodaOldRotation = 0, rodaRotation = 0, rodaPicked = 0;
        const rodaColor = d3.scale.category20();
        let selectedMembers = [];
        let allMembers = [];

        // Simpan semua anggota dari select
        document.addEventListener('DOMContentLoaded', function() {
            const selectElement = document.getElementById('roda-anggota-select');
            const options = selectElement.querySelectorAll('option[value!=""]');
            options.forEach(option => {
                allMembers.push({
                    id: option.value,
                    name: option.getAttribute('data-name')
                });
            });

            // Load saved data
            loadSavedData();
        });

        function loadSavedData() {
            const savedData = localStorage.getItem('arisanSelectedMembers');
            if (savedData) {
                selectedMembers = JSON.parse(savedData);
                updateSelectedMembersList();
                updateSelectOptions();
                if (selectedMembers.length > 0) {
                    createRodaWheel(selectedMembers.map(m => m.name));
                }
            }
        }

        function updateSelectedMembersList() {
            const container = document.getElementById('roda-selected-members');
            if (selectedMembers.length === 0) {
                container.innerHTML = '<span style="color: #a0aec0; font-size: 14px;">Belum ada peserta</span>';
                document.getElementById('roda-generate-wheel').disabled = true;
            } else {
                container.innerHTML = selectedMembers.map(member =>
                    `<span class="roda-selected-item">
                        ${member.name}
                        <span class="roda-remove-item" onclick="removeMember('${member.id}')">✕</span>
                    </span>`
                ).join('');
                document.getElementById('roda-generate-wheel').disabled = false;
            }
        }

        function updateSelectOptions() {
            const selectElement = document.getElementById('roda-anggota-select');
            const selectedIds = selectedMembers.map(m => m.id);

            // Reset select
            selectElement.value = "";

            // Hide/show options based on selection
            Array.from(selectElement.options).forEach(option => {
                if (option.value && selectedIds.includes(option.value)) {
                    option.style.display = 'none';
                } else {
                    option.style.display = 'block';
                }
            });
        }

        document.getElementById('roda-add-member').addEventListener('click', function() {
            const selectElement = document.getElementById('roda-anggota-select');
            const selectedOption = selectElement.options[selectElement.selectedIndex];

            if (!selectedOption.value) {
                alert("⚠️ Pilih anggota terlebih dahulu!");
                return;
            }

            const memberId = selectedOption.value;
            const memberName = selectedOption.getAttribute('data-name');

            // Check if already added
            if (selectedMembers.some(m => m.id === memberId)) {
                alert("⚠️ Anggota sudah ditambahkan!");
                return;
            }

            selectedMembers.push({ id: memberId, name: memberName });
            localStorage.setItem('arisanSelectedMembers', JSON.stringify(selectedMembers));

            updateSelectedMembersList();
            updateSelectOptions();
        });

        function removeMember(memberId) {
            selectedMembers = selectedMembers.filter(m => m.id !== memberId);
            localStorage.setItem('arisanSelectedMembers', JSON.stringify(selectedMembers));

            updateSelectedMembersList();
            updateSelectOptions();

            // Rebuild wheel if it exists
            if (rodaSvg && selectedMembers.length > 0) {
                createRodaWheel(selectedMembers.map(m => m.name));
            } else if (selectedMembers.length === 0 && rodaSvg) {
                rodaSvg.remove();
                rodaSvg = null;
            }
        }

        function createRodaWheel(names) {
            if (rodaSvg) {
                rodaSvg.remove();
                rodaOldRotation = 0;
                rodaRotation = 0;
            }

            const data = names.map((name, index) => ({ label: name, value: index + 1 }));
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
                .attr("transform", function (d) {
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
                const rng = Math.floor((Math.random() * 1440) + 360);
                rodaRotation = (Math.round(rng / ps) * ps);
                rodaPicked = Math.round(data.length - (rodaRotation % 360) / ps);
                rodaPicked = rodaPicked >= data.length ? (rodaPicked % data.length) : rodaPicked;

                rodaRotation += 90 - Math.round(ps / 2);
                rodaVis.transition()
                    .duration(3000)
                    .attrTween("transform", rotTweenRoda)
                    .each("end", function () {
                        const winnerName = data[rodaPicked].label;

                        // Hapus pemenang dari selectedMembers
                        selectedMembers = selectedMembers.filter(m => m.name !== winnerName);
                        localStorage.setItem('arisanSelectedMembers', JSON.stringify(selectedMembers));

                        // Tampilkan pemenang
                        displayRodaWinner(winnerName);

                        // Buat ulang roda dengan nama yang tersisa setelah 2 detik
                        setTimeout(() => {
                            updateSelectedMembersList();
                            updateSelectOptions();

                            if (selectedMembers.length > 0) {
                                createRodaWheel(selectedMembers.map(m => m.name));
                            } else {
                                alert("🎊 Semua peserta sudah mendapat giliran!");
                            }
                        }, 2000);
                    });
            }

            function rotTweenRoda() {
                const i = d3.interpolate(rodaOldRotation % 360, rodaRotation);
                return function (t) {
                    return `rotate(${i(t)})`;
                };
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
                localStorage.removeItem('arisanSelectedMembers');
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

