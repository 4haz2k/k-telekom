<template>
    <div class="form-signin w-50 mx-auto" style="margin-top: 100px">
        <form class="text-start">
            <h1 class="h3 mb-3 fw-normal text-center">Добавить оборудование</h1>

            <div v-if="errors_exist" class="alert alert-danger text-start" role="alert">
                Ошибка валидации
                <ul v-for="error in errors">
                    <li>{{ error.message }}</li>
                </ul>
            </div>

            <div class="form-row">
                <div class="col">
                    <label for="floatingSelect">Тип оборудования</label>
                    <select v-model="selectedType" id="floatingSelect" :required="true" class="form-control bg-dark my-2 text-white">
                        <option v-for="equipmentType in equipmentTypes.data" v-bind:value="equipmentType.id" >{{ equipmentType.id }} / {{ equipmentType.name }} / {{ equipmentType.mask }}</option>
                    </select>
                </div>
                <div class="col">
                    <pagination :data="equipmentTypes" @pagination-change-page="getEquipmentTypes" class="bg-dark mt-4">
                        <span slot="prev-nav">Назад</span>
                        <span slot="next-nav">Далее</span>
                        <span slot="sr-only">Текущее</span>
                    </pagination>
                </div>
            </div>
            <div class="bg-dark text-white mt-4">
                Серийные номера
            </div>
            <div class="row">
                <div v-for="(serial_number, index) in serial_numbers" :key="index">
                    <div class="ml-2 mt-2">
                        <input
                            v-model="serial_number.number"
                            placeholder="Введите серийный номер"
                            class="form-control bg-dark my-2 text-white"
                            :required="true"
                        />
                        <div class="d-flex justify-content-between">
                            <button
                                type="button"
                                class="rounded-md border px-3 py-2 bg-dark text-white"
                                @click="addMore()"
                            >
                                Добавить
                            </button>
                            <button
                                type="button"
                                class="ml-2 rounded-md border px-3 py-2 bg-dark text-white"
                                @click="remove(index)"
                                v-show="index !== 0"
                            >
                                Удалить
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <label for="floatingDescription">Примечание</label>
                <textarea v-model="description" type="text" class="form-control bg-dark my-2 text-white" id="floatingDescription"></textarea>
            </div>

            <button @click.prevent="saveEquipments" class="w-100 btn btn-lg btn-primary mt-4" type="submit">Добавить</button>
        </form>
    </div>
</template>

<script>
import pagination from "laravel-vue-pagination";

export default {
    name: "EquipmentAdd",

    components:{
        pagination
    },

    mounted() {
        this.getEquipmentTypes()
    },

    data() {
        return {
            errors: [],
            errors_exist: false,
            error_message: null,
            equipmentTypes: {
                type: Object,
                default: null,
            },
            serial_numbers: [{

            }],
            selectedType: null,
            description: null,
        }
    },

    methods: {
        async getEquipmentTypes(page = 1) {
            await axios.get('/api/equipment-type?page=' + page).then(response => {
                this.equipmentTypes = response.data
            })
        },

        async saveEquipments() {
            if (this.checkForm()) {
                await axios.post('/api/equipment', {
                    type_id: this.selectedType,
                    serial_numbers: this.serial_numbers,
                    description: this.description
                }).then(response => {
                    this.$notify({
                        title: 'Сохранение новых оборудований',
                        text: response.data.message,
                        type: 'success',
                    });
                    this.$router.push({ name: "equipments.list" })
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors_exist = true;
                        Object.values(error.response.data.errors).map(error => {
                            Object.values(error).map(message => {
                                this.errors.push({ message: message })
                            })
                        })
                    }
                })
            }
        },

        addMore() {
            this.serial_numbers.push({
                number: '',
            });
        },

        remove(index) {
            this.serial_numbers.splice(index, 1);
        },

        checkForm() {
            this.errors_exist = false;
            this.errors = [];

            if (!this.selectedType) {
                this.errors.push({ message: "Необходимо выбрать тип оборудования" });
            }

            if (this.errors.length > 0) {
                this.errors_exist = true;
                return false;
            }

            return true;
        },

    },
}
</script>

<style scoped>

</style>
