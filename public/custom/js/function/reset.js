const func_reset = {
	form($this){
		$this.each(function (idx) {
			if ($(this).hasClass('single-select')) {
				$(this).val('').trigger('change')
			} else {
				$(this).val('')
			}
		})
	}
}

export default func_reset
