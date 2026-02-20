const func_validator = {
	form($this){
		let text = ""

		$this.each(function (idx) {
			if (!$(this).val()) {
				$(this).addClass('show-alert')
				$(this).siblings(".select2-container").addClass('show-alert')
				if (!text) {
					text = $(this)[0].id.replace(/-/g, ' ').replace(/input /g, '')
					text = `${text[0].toUpperCase()}${text.slice(1)} wajib diisi!`
				}
			}
		})
		return text
	}
}

export default func_validator
