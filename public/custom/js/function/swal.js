import var_swal from "../variable/swal.js"

const func_swal = {
	confirm(object = {}){
		object = $.extend({}, var_swal.confirm, object)

		return Swal.fire(object)
	},
	error(object = {}){
		object = $.extend({}, var_swal.error, object)

		return Swal.fire(object)
	},
	success(object = {}){
		object = $.extend({}, var_swal.success, object)

		return Swal.fire(object)
	},
	warning(object = {}) {
		object = $.extend({}, var_swal.warning, object)

		return Swal.fire(object)
	},
	confirmEdit(object = {}){
		object = $.extend({}, var_swal.confirmEdit, object)

		return Swal.fire(object)
	},
}

export default func_swal
