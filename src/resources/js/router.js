import {createRouter, createWebHistory} from "vue-router";
import IndexPage from "./views/IndexPage";
import LpuCreate from "./views/lpu/LpuCreate";
import LpuShow from "./views/lpu/LpuShow";


const routes = [
    {
        path: '/',
        name: IndexPage,
        component: IndexPage
    },
    {
        path: '/lpu/create',
        name: LpuCreate,
        component: LpuCreate
    },
    {
        path: '/lpu/:id',
        name: LpuShow,
        component: LpuShow
    },

];

const router = createRouter({
    routes,
    base: process.env.APP_URL,
    history: createWebHistory(),
    // history: createWebHistory(process.env.BASE_URL),
    linkActiveClass: 'active'
});

export default router
