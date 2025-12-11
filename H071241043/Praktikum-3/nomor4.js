const readline = require("readline");

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout,
});

function tanyaInput(pertanyaan) {
  return new Promise((resolve) => {
    rl.question(pertanyaan, resolve);
  });
}

async function main() {
  const angkaRahasia = Math.floor(Math.random() * 100) + 1;
  let jumlahTebakan = 0;
  let tebakanBenar = false;

  console.log("Selamat datang di permainan tebak angka!");
  console.log("Saya telah memilih angka antara 1 dan 100.");

  while (!tebakanBenar) {
    let inputValid = false;
    let tebakan;

    while (!inputValid) {
      const input = await tanyaInput(
        "Masukkan salah satu dari angka 1 sampai 100: "
      );

      if (!input.trim()) {
        console.log("ERROR: Input tidak boleh kosong!");
        continue;
      }

      tebakan = parseInt(input);

      if (isNaN(tebakan)) {
        console.log("ERROR: Input harus berupa angka!");
      } else if (tebakan < 1 || tebakan > 100) {
        console.log("ERROR: Angka harus antara 1 dan 100!");
      } else {
        inputValid = true;
      }
    }

    jumlahTebakan++;

    if (tebakan === angkaRahasia) {
      tebakanBenar = true;
      console.log(
        `Selamat! kamu berhasil menebak angka ${angkaRahasia} dengan benar.`
      );
      console.log(`Sebanyak ${jumlahTebakan}x percobaan.`);
    } else if (tebakan > angkaRahasia) {
      console.log("Terlalu tinggi! Coba lagi.");
    } else {
      console.log("Terlalu rendah! Coba lagi.");
    }
  }

  rl.close();
}

main();
