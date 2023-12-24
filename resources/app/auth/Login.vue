<script setup>

import { ref } from 'vue'
import {doPost} from '../reusable/api'
const email = ref(null)
const email_error = ref(null)
const password = ref(null)
const password_error = ref(null)



function Login(e) {
    e.preventDefault()
    email_error.value = email.value == null || '' ? 'Enter Email Address' : null;
    password_error.value = password.value == null || '' ? 'Enter password Address' : null;
    if (email.value !==null && password.value !== null) {
        const credentials = {
            email: email.value,
            password: password.value,
        };
        doPost( '/api/auth/login',credentials).then(res=>{
           if (res.data.status ==='success') {
               localStorage.setItem('access_token', res.data.access_token)
               localStorage.setItem('current_user', res.data.user)
               localStorage.setItem('user_role', res.data.role)
               window.location.href = '/departments'
           }
        }).catch(error=>{
            console.log(error)
        })
    }
}

</script>

<template>
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="col-md-8 col-lg-7 col-xl-6">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.svg"
                         class="img-fluid" alt="Phone image">
                </div>
                <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="text-center">Login {{endpoint}}</h3>
                            </div>
                            <div class="card-body">
                                <form @submit="Login($event)">
                                    <!-- Email input -->
                                    <div class="form-outline mb-4">
                                        <input v-model="email" type="email" class="form-control form-control-lg" />
                                        <label class="form-label">Email address</label>
                                        <div class="col-md-6">
                                            <span class="my-2 text-danger form-label" v-if="email_error !== null">{{ email_error }}</span>
                                        </div>
                                    </div>
                                    <!-- Password input -->
                                    <div class="form-outline mb-4">
                                        <input v-model="password" type="password" class="form-control form-control-lg" />
                                        <label class="form-label">Password</label>
                                        <div class="col-md-6">
                                            <span class="my-2 text-danger form-label" v-if="password_error !== null">{{ password_error }}</span>
                                        </div>
                                    </div>
                                    <!-- Submit button -->
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
                                </form>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>

</style>
