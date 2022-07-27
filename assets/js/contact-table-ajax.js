let currentPage = 0;
const limit = 10;

async function getContactsPage(page, limit, orderby, ascending) {
  let pageObj;

  await jQuery.get(
    contacts_mgr_table_ajax.ajax_url,
    {
      action: "cm_get_contact_page",
      _ajax_nonce: contacts_mgr_table_ajax.nonce,
      page,
      limit,
      orderby,
      ascending,
    },
    function ({ success, data }) {
      pageObj = data;
    }
  );

  return pageObj;
}

async function renderTable(
  page = 0,
  limit = 10,
  orderby = "id",
  ascending = true
) {
  jQuery("#next_button").prop("disabled", true);
  jQuery("#back_button").prop("disabled", true);

  jQuery("#page_no").text(currentPage + 1);

  const messageEl = jQuery(".cm-contacts-mgr.table-loading-message");
  const tableEl = jQuery(".cm-contacts-mgr.table-body");

  messageEl.text("Loading...");
  messageEl.show();

  tableEl.empty();

  const pageObj = await getContactsPage(page, limit, orderby, ascending);

  const totalPages = Math.ceil(pageObj.total / pageObj.limit);

  for (let contact of pageObj.data) {
    const row = jQuery("<tr/>");

    row.append(jQuery("<td/>").text(contact.id));
    row.append(jQuery("<td/>").text(contact.name));
    row.append(jQuery("<td/>").text(contact.email));
    row.append(jQuery("<td/>").text(contact.phone));
    row.append(jQuery("<td/>").text(contact.address));

    tableEl.append(row);
  }

  if (totalPages !== currentPage + 1) {
    jQuery("#next_button").prop("disabled", false);
  }

  if (currentPage !== 0) {
    jQuery("#back_button").prop("disabled", false);
  }

  messageEl.hide();

  if (pageObj.data.length === 0) {
    messageEl.text("No Data");
    messageEl.show();
  }
}

(async function () {
  renderTable(0, 10);

  jQuery("#back_button").on("click", function () {
    if (currentPage > 0) renderTable(--currentPage, limit);
  });

  jQuery("#next_button").on("click", function () {
    renderTable(++currentPage, limit);
  });
})();