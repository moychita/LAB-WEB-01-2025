const readline = require("readline");

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout,
});

const hariDalamMinggu = [
  "Minggu",
  "Senin",
  "Selasa",
  "Rabu",
  "Kamis",
  "Jumat",
  "Sabtu",
];

function tanyaInput(pertanyaan) {
  return new Promise((resolve) => {
    rl.question(pertanyaan, resolve);
  });
}

function validasiHari(hari) {
  return hariDalamMinggu.some(
    (namaHari) => namaHari.toLowerCase() === hari.toLowerCase()
  );
}

function hitungHariMendatang(hariSekarang, jumlahHari) {
  const indexHariSekarang = hariDalamMinggu.findIndex(
    (hari) => hari.toLowerCase() === hariSekarang.toLowerCase()
  );

  const totalIndex = (indexHariSekarang + jumlahHari) % 7;
  const indexHariMendatang = totalIndex >= 0 ? totalIndex : totalIndex + 7;

  return hariDalamMinggu[indexHariMendatang];
}

async function main() {
  try {
    let hariInput;
    while (true) {
      hariInput = await tanyaInput("Masukkan hari : ");

      if (!hariInput.trim()) {
        console.log("ERROR: Hari tidak boleh kosong!");
        continue;
      }

      if (validasiHari(hariInput)) {
        break;
      } else {
        console.log("ERROR: Hari tidak valid!");
        console.log("Hari yang valid:", hariDalamMinggu.join(", "));
      }
    }

    let jumlahHariInput;
    while (true) {
      jumlahHariInput = await tanyaInput("Masukkan hari yang akan datang : ");

      if (!jumlahHariInput.trim()) {
        console.log("ERROR: Jumlah hari tidak boleh kosong!");
        continue;
      }

      const jumlahHari = parseInt(jumlahHariInput);

      if (isNaN(jumlahHari)) {
        console.log("ERROR: Jumlah hari harus berupa angka!");
      } else if (jumlahHari < 0) {
        console.log("ERROR: Jumlah hari tidak boleh negatif!");
      } else {
        jumlahHariInput = jumlahHari;
        break;
      }
    }

    const hariMendatang = hitungHariMendatang(hariInput, jumlahHariInput);

    console.log(
      `Output : ${jumlahHariInput} hari setelah ${hariInput} adalah ${hariMendatang}`
    );
  } catch (error) {
    console.log("Terjadi kesalahan:", error.message);
  } finally {
    rl.close();
  }
}

main();
