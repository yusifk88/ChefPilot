import axios from "axios";
axios.defaults.baseURL = 'https://cpapi.flobaze.com/api';
axios.defaults.headers.common['Authorization'] = "Bearer "+localStorage.getItem("token");
axios.defaults.headers.common['Accept'] = "application/json";
axios.defaults.headers.post['Content-Type']="application/json";

window.axios=axios;