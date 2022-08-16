<template>
    <main-layout>
        <pop-up :show="showPopUp">
            <template v-slot:header>
                Требуется подтверждение:
            </template>
            <template v-slot:body>
                <div> Удалить эту версию файла?
                </div>
            </template>
            <template v-slot:footer>
                <div class="line">
                    <div style="display:inline-block; width: 40%">
                        <button-red @click="deleteItem(); showPopUp = false;">да</button-red>
                    </div>
                    <div style="display:inline-block; width: 40%">
                        <button-green @click="showPopUp = false;">нет
                        </button-green>
                    </div>
                </div>
            </template>
        </pop-up>
        <a href="/lpu/create" style="text-decoration: none;">новая версия</a>
        <a :href="'/api/v1/lpu/' + this.$route.params.id + '/download'" style="text-decoration: none; margin-left: 10px; color: #0b2e13">скачать</a>
<a style="text-decoration: none; margin-left: 10px;color: darkred" @click="askDelete">удалить</a>

        <lpu-form :file="file" :version="version" @save="saveEvent($event)"></lpu-form>
    </main-layout>
</template>

<script>
import MainLayout from "../layouts/MainLayout";
import LpuForm from "../../components/lpu/LpuForm";
import ButtonGreen from "../../components/UI/ButtonGreen";
import PopUp from "../../components/UI/PopUp";
import ButtonRed from "../../components/UI/ButtonRed";

export default {
    name: "LpuShow",
    components: {ButtonRed, PopUp, ButtonGreen, LpuForm, MainLayout},
    data() {
        return {
            lpu: [],
            file: null,
            version: '',
            showPopUp: false
        }
    },
    methods: {
        async fileRequest() {
            return await axios.get('/api/v1/lpu/' + this.$route.params.id);
        },
        getFile() {
            this.fileRequest().then(response => {
                if (response.data.success === true) {
                    this.file = response.data.data.file;
                    this.version = response.data.data.version;
                } else {
                    window.location.href = '/'
                }
            }).catch(() => {
                window.location.href = '/'
            })
        },
        saveEvent($event) {
            this.lpu = $event.lpu;
            this.version = $event.version;
            this.lpuUpdate();
        },
        async lpuUpdateRequest() {
            return axios.put('/api/v1/lpu/' + this.$route.params.id + '/update', {lpu: this.lpu, version:this.version});
        },
        lpuUpdate() {
            this.lpuUpdateRequest().then(response => {
                if (response.data.success === true) {
                    if (Number(this.$route.params.id) !== Number(response.data.data.id)) {
                        window.location.href = '/lpu/' + response.data.data.id;
                    } else {
                        alert('Сохранено!')
                    }
                } else {
                    console.log('LpuShow error lpu update ', response.data)
                }
            })
        },
        askDelete() {
            this.showPopUp = true;
        },
        deleteRequest() {
            return axios.delete('/api/v1/lpu/' + this.$route.params.id)
        },
        deleteItem() {
            this.deleteRequest().then(response => {
                if (response.data.success === true) {
                    this.$router.push('/');
                }
            })
        }
    },
    mounted() {
        let token = document.head.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }
        this.getFile();
    }
}
</script>

<style scoped>

</style>
