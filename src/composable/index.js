export const sendAJAX = (action, payload = {}, callback, onFail) => {
  const prefix = "cm";
  payload.action = `${prefix}_${action}`;

  window.jQuery
    .post(contactsMgrAdmin.ajax_url, payload, (data) => {
      callback(data);
    })
    .fail(onFail());
};
