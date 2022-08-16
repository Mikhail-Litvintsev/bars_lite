require('./bootstrap');

import {createApp} from "vue";
import components from "./components";
import views from "./views";
import router from "./router";

const app = createApp({
});

components.forEach(component => {
    app.component(component.name, component)
})
views.forEach(component => {
    app.component(component.name, component)
})

app.use(router).mount('#app');
