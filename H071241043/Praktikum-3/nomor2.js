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

function countPrice(category, price) {
  const categoryLowerCase = category.toLowerCase();

  if (categoryLowerCase === "elektronik") {
    return price - price * 0.1;
  } else if (categoryLowerCase === "pakaian") {
    return price - price * 0.2;
  } else if (categoryLowerCase === "makanan") {
    return price - price * 0.05;
  } else {
    return price;
  }
}

async function main() {
  try {
    const kategoriValid = ["elektronik", "pakaian", "makanan"];
    const diskonKategori = {
      elektronik: "10%",
      pakaian: "20%",
      makanan: "5%",
    };

    let hargaInput;
    while (true) {
      const hargaStr = await tanyaInput("Masukkan harga barang: ");

      if (!hargaStr.trim()) {
        console.log("ERROR: Harga tidak boleh kosong!");
        continue;
      }

      const harga = parseFloat(hargaStr);

      if (isNaN(harga)) {
        console.log("ERROR: Harga harus berupa angka!");
      } else if (harga <= 0) {
        console.log("ERROR: Harga harus lebih besar dari 0!");
      } else {
        hargaInput = harga;
        break;
      }
    }

    let kategoriInput;
    while (true) {
      kategoriInput = await tanyaInput(
        "Masukkan jenis barang (Elektronik,Pakaian,Makanan,Lainnya): "
      );

      if (!kategoriInput.trim()) {
        console.log("ERROR: Kategori tidak boleh kosong!");
        continue;
      }

      if (kategoriValid.includes(kategoriInput.toLowerCase())) {
        break;
      } else {
        console.log("ERROR: Kategori tidak valid!");
        console.log("Kategori yang valid: Elektronik, Pakaian, Makanan");
      }
    }

    const hargaSetelahDiskon = countPrice(kategoriInput, hargaInput);

    console.log(`Harga awal: Rp ${hargaInput.toLocaleString()}`);
    console.log(
      `Diskon: ${diskonKategori[kategoriInput.toLowerCase()] || "0%"}`
    );
    console.log(
      `Harga setelah diskon: Rp ${hargaSetelahDiskon.toLocaleString()}`
    );
  } catch (error) {
    console.log("Terjadi kesalahan:", error.message);
  } finally {
    rl.close();
  }
}

main();
