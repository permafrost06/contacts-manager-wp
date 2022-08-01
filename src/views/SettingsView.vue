<script setup>
import { ElMessage } from "element-plus";
import { ref } from "vue";
import { getAJAX, getSetting, updateSetting } from "../composable";

const settings = ref({});

const settingsObj = ref({});

await getAJAX("get_all_settings", {}, ({ success, data }) => {
  settingsObj.value = data;
});

for (let setting in settingsObj.value) {
  if (setting == "table_limit")
    settings.value[setting] = Number(await getSetting(setting));
  else settings.value[setting] = await getSetting(setting);
}

const onSubmit = () => {
  for (let setting in settings.value) {
    if (!updateSetting(setting, settings.value[setting])) {
      ElMessage({
        message: "Settings could not be updated",
        type: "error",
      });

      return;
    }
  }

  ElMessage({
    message: "Settings updated successfully",
    type: "success",
  });
};
</script>

<template>
  <el-form @submit.prevent="onSubmit" :model="settings" label-width="300px">
    <el-form-item
      v-for="(attrs, setting) in settingsObj"
      :key="setting"
      :prop="setting"
      :label="attrs.desc"
    >
      <el-col :span="3">
        <el-input-number
          v-if="attrs.type == 'numeric'"
          v-model="settings[setting]"
          :min="attrs.min"
          :max="attrs.max"
        />
        <el-select v-if="attrs.type == 'select'" v-model="settings[setting]">
          <el-option
            v-for="item in attrs.options"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
        <el-color-picker
          v-if="attrs.type == 'color'"
          v-model="settings[setting]"
        />
      </el-col>
    </el-form-item>
    <el-form-item>
      <el-button native-type="submit" type="primary">Save settings</el-button>
      <router-link :to="{ name: 'Contacts Table' }">
        <el-button>Go back</el-button>
      </router-link>
    </el-form-item>
  </el-form>
</template>
