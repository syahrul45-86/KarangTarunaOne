<div style="position: relative; width:400px; margin:auto;">

    {{-- TEMPLATE --}}
    <img src="{{ asset('storage/' . $templatePath) }}" style="width:100%;">

    {{-- FOTO DARI USER --}}
    <img src="{{ asset('storage/' . $user->image) }}"
         style="
            position:absolute;
            top:36px;
            left:50%;
            transform:translateX(-50%);
            width:170px;
            height:170px;
            border-radius:50%;
            object-fit:cover;
         ">

    {{-- NAMA --}}
    <div style="
        position:absolute;
        top:230px;
        width:100%;
        text-align:center;
        font-weight:bold;
        font-size:24px;
        color:white;
    ">
        {{ strtoupper($user->name) }}
    </div>

    {{-- QR --}}
    <img src="{{ asset('storage/' . $qrPath) }}"
         style="
            position:absolute;
            bottom:110px;

            left:50%;
            transform:translateX(-50%);
            width:160px;
         ">
</div>
