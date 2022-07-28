import { ElMessage } from "element-plus";

const onFailDefault = (xhr) => {
  const res = JSON.parse(xhr.responseText);
  const err_msg = res.data.message ? res.data.message : res.data.error;

  ElMessage({
    message: "AJAX Request Failed - " + err_msg,
    type: "error",
  });
};

const callbackDefault = (data) => {
  console.log("No callback defined. Dumping data in console.");
  console.debug(data);
};

export const getAJAX = async (
  action,
  payload = {},
  callback = callbackDefault,
  onFail = onFailDefault
) => {
  const prefix = "cm";
  payload.action = `${prefix}_${action}`;
  payload._ajax_nonce = contactsMgrAdmin.nonce;

  await window.jQuery
    .get(contactsMgrAdmin.ajax_url, payload, (data) => {
      callback(data);
    })
    .fail(onFail);
};

export const postAJAX = async (
  action,
  payload = {},
  callback = callbackDefault,
  onFail = onFailDefault
) => {
  const prefix = "cm";
  payload.action = `${prefix}_${action}`;
  payload._ajax_nonce = contactsMgrAdmin.nonce;

  await window.jQuery
    .post(contactsMgrAdmin.ajax_url, payload, (data) => {
      callback(data);
    })
    .fail(onFail);
};

export const getSetting = async (option) => {
  let value;

  await postAJAX(
    "get_setting",
    {
      option_name: option,
    },
    ({ success, data }) => {
      value = data;
    }
  );

  return value;
};

export const updateSetting = async (option, value) => {
  let success;

  await postAJAX(
    "update_setting",
    {
      option_name: option,
      option_value: value,
    },
    (data) => {
      success = data.success;
    }
  );

  return success;
};
