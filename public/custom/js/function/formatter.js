import parse from "./parse.js"

const func_formatter = {
	formatNumberId(angka, prefix = ''){
		// let number_string = angka.toString().replace(/[^,\d]/g, "")
		let isNegative = angka.toString().indexOf("-")
		let number_string = parse.onlyNumber(angka).replace(/^0+(?=\d)/, '')

		let split = number_string.split(",")
		let sisa = split[0].length % 3
		let rupiah = split[0].substr(0, sisa);
		let ribuan = split[0].substr(sisa).match(/\d{3}/gi)

		// tambahkan titik jika yang di input sudah menjadi angka ribuan
		if (ribuan) {
			let separator = sisa ? "." : ""
			rupiah += separator + ribuan.join(".")
		}

		rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah

		let text = prefix+rupiah

		if (isNegative !== -1) text = `${prefix} -${rupiah}`;
		if (!rupiah) text = '';

		return text;
		return prefix === undefined ? rupiah : (isNegative !== -1 ? `${prefix} -${rupiah}` : prefix+rupiah);
		// return prefix === undefined ? rupiah : rupiah ? "Rp. " + rupiah : ""
	},
	formatRupiah(number) {
		return new Intl.NumberFormat('id-ID', {
			style: 'currency',
			currency: 'IDR',
			minimumFractionDigits: 0
		}).format(number)
	}
}

export default func_formatter
