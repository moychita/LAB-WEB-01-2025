@extends('layouts.master')

@section('content')
<h2>Kuliner Khas NTB</h2>
<p>Nikmati cita rasa khas Lombok — pedas, segar, dan penuh rempah yang menggugah selera.</p>

<div class="card-grid">
    <x-card image="taliwang.jpg" title="Ayam Taliwang" description="Ayam bakar pedas khas Lombok dengan bumbu cabai merah dan terasi." />
    <x-card image="plecing.jpg" title="Plecing Kangkung" description="Kangkung segar dengan sambal tomat pedas yang segar dan khas." />
    <x-card image="bulayak.jpg" title="Sate Bulayak" description="Sate daging sapi dengan lontong daun aren dan bumbu kacang kental." />
</div>
@endsection