import axios from 'axios'
import store from '../store'

const http = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
  timeout: 100000,
  headers: { "Content-Type": "application/json" },
})

http.interceptors.request.use(config => {
  if (store.getters.token) {
    config.headers['Authorization'] = 'Bearer ' + store.getters.token
  }
  return config
}, error => {
  // Do something with request error`
  console.log('Error in config interceptors: ') // for debug
  console.log(error) // for debug
  Promise.reject(error)
})

http.interceptors.response.use(
  response => {
    const res = response
    if (res.status < 200 || res.status > 299) {
      if (res.code === 50008 || res.code === 50012 || res.code === 50014) {
        // La faut handle le fin de validité du token, ca serait nice
        store.dispatch('FedLogOut').then(() => {
          location.reload()
        })
      }
      return Promise.reject(new Error('error'))
    } else {
        if ('content-disposition' in response.headers &&
            response.headers['content-disposition'].includes('attachment')) {
          return response
        }
      return response.data
    }
  },
  error => {
    /* Message({
      message: error.message,
      type: 'error',
      duration: 5 * 1000
    }) */
    return Promise.reject(error)
  }
)

export default http