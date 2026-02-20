// import "./axios.min.js"
// require('axios.min.js')

const postRequest = (route, object={}) => {
	const sendRequest = axios.post(route, object)
	.then((response) => {return response})
	.catch(function(error){
		error.data = error.response.data
		error.status = error.response.status
		console.error(error)
		return error
	})
	return sendRequest
}

const getRequest = (route, object={}) => {
	object.is_api = true
	const sendRequest = axios.get(route,{params: object})
	.then((response) => {return response})
	.catch(function(error){
		error.data = error.response.data
		error.status = error.response.status
		console.error(error)
		return error
	})
	return sendRequest
}
