export const sendAJAX = (action, payload = {}, callback, onFail) => {
  payload.action = action;

  window.jQuery
    .post(contactsMgrAdmin.ajax_url, payload, (data) => {
      callback(data);
    })
    .fail(onFail);
};
