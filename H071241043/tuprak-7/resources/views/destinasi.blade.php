@extends('layouts.master')

@section('content')
<h2>Destinasi Alam NTB</h2>
<p>Jelajahi keindahan alam Lombok dan sekitarnya — dari pegunungan hingga pantai tropis yang memukau.</p>

<div class="card-grid">
    <x-card image="rinjani.jpg" title="Gunung Rinjani" description="Gunung tertinggi di NTB dengan danau Segara Anak yang indah." />
    <x-card image="selong-belanak.jpg" title="Pantai Selong Belanak" description="Pantai berpasir putih dengan ombak tenang dan pemandangan menawan." />
    <x-card image="tiu-kelep.jpg" title="Air Terjun Tiu Kelep" description="Air terjun jernih di bawah kaki Gunung Rinjani yang eksotis." />
</div>
@endsection