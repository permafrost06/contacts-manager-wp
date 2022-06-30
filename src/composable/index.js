import { ElMessage } from "element-plus";

export const sendAJAX = (action, payload = {}, callback, onFail) => {
  const prefix = "cm";
  payload.action = `${prefix}_${action}`;
  payload._ajax_nonce = contactsMgrAdmin.nonce;

  if (!onFail) {
    onFail = () => {
      ElMessage({
        message: "AJAX Request Failed",
        type: "error",
      });
      console.log("AJAX Request Failed");
    };
  }

  window.jQuery
    .post(contactsMgrAdmin.ajax_url, payload, (data) => {
      callback(data);
    })
    .fail(onFail());
};
