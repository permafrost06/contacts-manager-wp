import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import { createPinia } from "pinia";
import ElementPlus from "element-plus";
import "element-plus/dist/index.css";

const app = createApp(App).use(createPinia());

app.use(router);
app.use(ElementPlus);

app.mount("#app");
