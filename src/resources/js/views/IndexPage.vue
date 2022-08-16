<template>
    <main-layout>
        <H1>Редактор json-файла</H1>

        <div>
            <button @click="routerCreate">Создать новый</button>
        </div>
        <div>
            <label>Редактировать существующий</label>
            <select v-model="filesList">
                <option v-for="item in filesList" :value="item.id" @click="routerShow(item.id)">{{ item.version }}
                </option>
            </select>
        </div>
            <label for="file">Загрузить файл</label>
            <input ref="file" id="file" name="file" type="file" @change="uploadFile">
    </main-layout>
</template>

<script>
import MainLayout from "./layouts/MainLayout";

export default {
    name: "IndexPage",
    components: {MainLayout},
    data() {
        return {
            filesList: [],
            csrfToken: '',
        }
    },
    methods: {
        routerCreate() {
            this.$router.push('/lpu/create');
        },
        routerShow(id) {
            this.$router.push('/lpu/' + id);
        },
        async filesListRequest() {
            return await axios.get('/api/v1/lpu');
        },
        getFilesList() {
            this.filesListRequest().then(response => {
                if (response.data.success === true) {
                    this.filesList = response.data.data;
                    console.log(response.data)
                }
            })
        },
        async uploadFileRequest(formData) {
            let token = document.head.querySelector('meta[name="csrf-token"]');
            if (token) {
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
                this.csrfToken =  token.content;
            }
            return await axios.post('/api/v1/lpu/upload', formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
        },
        uploadFile() {
            let file = this.$refs.file.files[0];
            let formData = new FormData();
            formData.append('file', file);
            this.uploadFileRequest(formData).then(response => {
                if (response.data.success === true) {
                    this.$router.push('/lpu/' + response.data.data.id)
                }
            })
        }
    },
    mounted() {
        this.getFilesList();
    }
}
</script>

<style scoped>

</style>
