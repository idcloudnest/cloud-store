const func_parse = {
	onlyNumber(num){
		return  num.toString().replace(/[^,\d]/g, "")
	}
}

export default func_parse
