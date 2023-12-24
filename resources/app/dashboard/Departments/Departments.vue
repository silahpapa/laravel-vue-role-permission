<script setup>
import ShbModal from 'shb-modal'
import globalRepo from '../../composable/global.js'
import Swal from "sweetalert2";

import {doGet,doPost} from "../../reusable/api.ts";
import {onBeforeMount, ref} from "vue";

const permissions = ref(null)
const name = ref(null)
const description = ref(null)

const { Toast } = globalRepo()
function getDepartments() {
    doGet('/api/departments/list').then((response) => {
        if (response.data.status ==='success') {
            permissions.value = response.data.data
        }
    })
}

const nameError = ref(null)
const descriptionError = ref(null)
const editId = ref(null)

function addDeparment() {
    nameError.value = null
    descriptionError.value = null
    const data = {
        name: name.value,
        description: description.value,
        editId: editId.value
    }
    const toastTitle = editId.value === null ? 'Department Added Successfully' : 'Department Updated Successfully'
    doPost('/api/departments/store', data).then((response) => {
        if (response.data.status === 'success') {
            Toast.fire({
                icon: 'success',
                title: toastTitle
            })
            getDepartments()
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

let departmentTitle = ref(null)
let modalId = ref(1)
function updateDepartment(permission) {
    nameError.value = null
    descriptionError.value = null
    if (permission === null) {
        modalId.value = 1
        departmentTitle.value = 'Add Department'
        name.value = null
        description.value = null
        editId.value = null
        return
    }
    modalId.value = permission.id
    departmentTitle.value = 'Edit Department'
    name.value = permission.name
    description.value = permission.description
    editId.value = permission.id
}
const allPermissions = ref(null)
const selectedDepartmentId = ref(null)
function managePermission(permission) {
    selectedDepartmentId.value = permission.id
    doGet('/api/departments/manage-permission', {department_id: permission.id}).then((response) => {
        if (response.data.status === 'success') {
            allPermissions.value = response.data.all_permissions
        }
    })
}
function formattedControllerName(controller) {
    return controller
        .replace("Controller", "")
        .replace(/([A-Z])/g, " $1")
        .trim();
}
function formatPermission(name) {
    return name.replace('_', ' ')
}
const addbtn = ref('addbtn')
function updatePermission() {
    const data = {
        allPermissions: allPermissions.value,
        department_id: selectedDepartmentId.value
    }
    doPost('/api/departments/update-permission', data).then((response) => {
        if (response.data.status === 'success') {
            Toast.fire({
                icon: 'success',
                title: 'Permission Updated Successfully'
            })
            getDepartments()
        }
    })
}
function deactivateDepartment(permission) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            doPost('/api/departments/deactivate', {department_id: permission.id}).then((response) => {
                if (response.data.status === 'success') {
                    Swal.fire(
                        'Deactivated!',
                        'Department has been deactivated.',
                        'success'
                    )
                    getDepartments()
                }
            })

        }
    })

}
getDepartments()

</script>
<template>
    <div class="card">
        <button type="button" :ref="addbtn" @click="updateDepartment(null)" class="btn btn-primary my-2 ms-4 col-2" data-bs-toggle="modal" :data-bs-target="'#deparmentModal'+modalId">
            Add Department
        </button>
        <shb-modal :id="'deparmentModal'+modalId" :title="departmentTitle">
            <form @submit.prevent="addDeparment">
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
            <tbody id="table-body" v-if="permissions">
            <tr v-for="permission in permissions" :key="permission.id">
                <td>{{ permission.id }}</td>
                <td>{{ permission.name }}</td>
                <td>{{ permission.description }}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary" @click="updateDepartment(permission)"  data-bs-toggle="modal" :data-bs-target="'#deparmentModal'+permission.id">Edit</button>
                    <button type="button" class="btn btn-sm mx-1 btn-danger" @click="deactivateDepartment(permission)">Delete</button>
                    <a class="btn btn-sm btn-warning" @click="managePermission(permission)" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                       Manage
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">Permissions</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div>
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item" v-for="(permissions,index) in allPermissions" :key="permissions.id">
                            <h2 class="accordion-header" :id="'heading'+index">
                                <button class="accordion-button" :class="index !==0 ? 'collapsed' :''" type="button" data-bs-toggle="collapse" :data-bs-target="'#collapse'+index" aria-expanded="true" :aria-controls="'collapse'+index">
                                    {{ formattedControllerName(permissions[0].controller) }}
                                </button>
                            </h2>
                            <div :id="'collapse'+index" class="accordion-collapse collapse" :class="index==0 ?'show':''" :aria-labelledby="'heading'+index" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="form-check" v-for="(permission,i) in permissions" :key="i">
                                        <input class="form-check-input" type="checkbox" v-model="permission.has_permission" :id="permission.id">
                                        <label class="form-check-label" :for="permission.id">
                                            {{ formatPermission(permission.name) }}
                                        </label>
                                    </div>
                                    <button class="btn my-2 btn-sm btn-primary" @click="updatePermission">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
</style>
