<script setup>
import globalRepo from '@app/composable/global.js'
import ShbModal from 'shb-modal'
import { ref } from 'vue'
import {doGet, doPost} from "../../reusable/api.ts";
import Swal from "sweetalert2";

const { Toast } = globalRepo()

const name = ref(null)
const description = ref(null)
const nameError = ref(null)
const descriptionError = ref(null)
const editId = ref(null)
const modalId = ref(1)
const RoleTitle = ref(null)
const roles = ref(null)

function addrole() {
    nameError.value = null
    descriptionError.value = null
    const data = {
        name: name.value,
        description: description.value,
        editId: editId.value
    }
    RoleTitle.value = editId.value == null ? 'Add Role' : 'Edit Role'
    const message = editId.value == null ? 'Role Added Successfully' : 'Role Updated Successfully'
    doPost('/api/roles/store', data).then((response) => {
        if (response.data.status === 'success') {
            Toast.fire({
                icon: 'success',
                title: message
            })
            getRoles()
        }
    }).catch(error => {
        if(Object.keys(error.response.data.errors).length > 0) {
            const errors = error.response.data.errors
            for (const key in errors) {
                if (errors.hasOwnProperty(key)) {
                    const messages = errors[key];
                    if (key === 'name') {
                        messages.forEach(message => {
                            nameError.value = message
                        });
                    }
                    if (key === 'description') {
                        messages.forEach(message => {
                            descriptionError.value = message
                        });
                    }

                }
            }

        }
    })
}
function getRoles() {
    doGet('/api/roles/list').then((response) => {
        if (response.data.status ==='success') {
            roles.value = response.data.data
        }
    })
}
function updateRole(role) {
    if (role === null) {
        editId.value = null
        name.value = null
        description.value = null
        return
    }
    editId.value = role.id
    name.value = role.name
    description.value = role.description
    modalId.value = role.id
}
function deactivateRole(role) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, deactivate it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            doPost('/api/roles/deactivate', {id: role.id}).then((response) => {
                if (response.data.status === 'success') {
                    Toast.fire({
                        icon: 'success',
                        title: 'Role Deactivated Successfully'
                    })
                    getRoles()
                }
            })
        }
    })
}
getRoles()
</script>

<template>
    <div class="card">
        <button type="button" @click="updateRole(null)" class="btn btn-primary my-2 ms-4 col-2" data-bs-toggle="modal" :data-bs-target="'#roleModal'+modalId">
            Add Role
        </button>
        <shb-modal :id="'roleModal'+modalId" :title="RoleTitle">
            <form @submit.prevent="addrole">
                <input type="hidden" v-if="editId" v-model="editId">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input v-model="name" type="text" id="name" class="form-control" placeholder="Name">
                    <span class="text-danger" v-if="nameError">{{nameError}}</span>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea v-model="description" class="form-control" placeholder="Description"></textarea>
                    <span class="text-danger" v-if="descriptionError">{{ descriptionError }}</span>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </shb-modal>
        <input type="text" id="search-input" class="form-control mb-2" placeholder="Search">
        <table id="my-table" class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody id="table-body" v-if="roles">
            <tr v-for="role in roles" :key="role.id">
                <td>{{ role.id }}</td>
                <td>{{ role.name }}</td>
                <td>{{ role.description }}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary" @click="updateRole(role)"  data-bs-toggle="modal" :data-bs-target="'#roleModal'+role.id">Edit</button>
                    <button type="button" class="btn btn-sm mx-1 btn-danger" @click="deactivateRole(role)">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>

</style>
