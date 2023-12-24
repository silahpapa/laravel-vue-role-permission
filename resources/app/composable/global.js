
import {doPost,doGet} from '../reusable/api.ts'
import Swal from 'sweetalert2'


const globalRepo = () => {

    const user = localStorage.getItem('current_user')
    const setUser = async function () {
        await doGet('/api/users/auth/user').then(res => {
            if (typeof res.data !== 'undefined') {
                localStorage.setItem('current_user', res.data.user)
                localStorage.setItem('user_role', res.data.user.role)
                const user = res.data.user
            } else {
                localStorage.setItem('current_user', null)
                localStorage.setItem('user_role', null)
            }
        }).catch(e => {
            console.log(e)
        })
    }

    const logoutUser = async function () {
        localStorage.removeItem('access_token')
        localStorage.removeItem('current_user')
        localStorage.removeItem('user_role')
    }
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })


    return {
        user,
        setUser,
        logoutUser,
        Toast
    }
}

export default globalRepo;
