import { ElMessage } from "element-plus";

const onFailDefault = () => {
  ElMessage({
    message: "AJAX Request Failed",
    type: "error",
  });
};

const callbackDefault = (data) => {
  console.log("No callback defined. Dumping data in console.");
  console.debug(data);
};

export const getAJAX = (
  action,
  payload = {},
  callback = callbackDefault,
  onFail = onFailDefault
) => {
  const prefix = "cm";
  payload.action = `${prefix}_${action}`;
  payload._ajax_nonce = contactsMgrAdmin.nonce;

  window.jQuery
    .get(contactsMgrAdmin.ajax_url, payload, (data) => {
      callback(data);
    })
    .fail(onFail);
};

export const postAJAX = (
  action,
  payload = {},
  callback = callbackDefault,
  onFail = onFailDefault
) => {
  const prefix = "cm";
  payload.action = `${prefix}_${action}`;
  payload._ajax_nonce = contactsMgrAdmin.nonce;

  window.jQuery
    .post(contactsMgrAdmin.ajax_url, payload, (data) => {
      callback(data);
    })
    .fail(onFail);
};
