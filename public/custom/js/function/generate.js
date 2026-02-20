const func_generate = {
	randomId(lnegth = 7){
		var text = ""
		var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"

		for (var i = 0; i < lnegth; i++) {
			text += possible.charAt(Math.floor(Math.random() * possible.length))
		}

		return text.toString()
	}
}

export default func_generate
