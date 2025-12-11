@extends('layouts.master')

@section('content')
<h2>Hubungi Kami</h2>
<p>Ingin tahu lebih banyak tentang wisata NTB? Kirim pesanmu melalui form berikut:</p>

<form class="contact-form" action="#" method="post">
    <label>Nama:</label>
    <input type="text" name="nama" placeholder="Masukkan nama kamu">

    <label>Email:</label>
    <input type="email" name="email" placeholder="Masukkan email kamu">

    <label>Pesan:</label>
    <textarea name="pesan" rows="5" placeholder="Tulis pesanmu di sini..."></textarea>

    <button type="submit">Kirim Pesan</button>
</form>
@endsection