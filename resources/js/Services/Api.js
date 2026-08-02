import axios from "axios";

const api = axios.create({
    baseURL: "/api",

    headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
    },

    timeout: 30000,
});

/*
|--------------------------------------------------------------------------
| Request Interceptor
|--------------------------------------------------------------------------
*/

api.interceptors.request.use(
    (config) => {

        const token = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");

        if (token) {
            config.headers["X-CSRF-TOKEN"] = token;
        }

        return config;
    },

    (error) => Promise.reject(error)
);

/*
|--------------------------------------------------------------------------
| Response Interceptor
|--------------------------------------------------------------------------
*/

api.interceptors.response.use(

    (response) => response,

    (error) => {

        if (error.response?.status === 401) {

            window.location.href = "/login";
        }

        if (error.response?.status === 403) {

            console.error("Permission Denied.");
        }

        if (error.response?.status === 419) {

            location.reload();
        }

        return Promise.reject(error);
    }
);

export default api;