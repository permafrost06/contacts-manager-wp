let limit;
let orderby;
let bg_color;

async function getContactsPage(page, limit, orderby, ascending) {
  let pageObj;

  await jQuery.get(
    contacts_manager_ajax.ajax_url,
    {
      action: "cm_get_contact_page",
      _ajax_nonce: contacts_manager_ajax.nonce,
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
  tableEls,
  page = 0,
  limit = 10,
  orderby = "id",
  ascending = true
) {
  const messageEls = tableEls.siblings(".table-loading-message");

  const next_button = tableEls.siblings(".table-nav").find(".next-button");
  const prev_button = tableEls.siblings(".table-nav").find(".prev-button");

  next_button.prop("disabled", true);
  prev_button.prop("disabled", true);

  const page_num = page + 1;

  tableEls.siblings(".table-nav").find(".page-number").text(page_num);

  const animateText = setInterval(() => {
    messageEls.text((index, value) => {
      if (value.length < 10) value += ".";
      else value = "Loading";
      return value;
    });
  }, 200);

  messageEls.show();

  const tableBody = tableEls.find("tbody.cm-contacts-mgr");

  tableBody.empty();

  const pageObj = await getContactsPage(page, limit, orderby, ascending);

  const totalPages = Math.ceil(pageObj.total / pageObj.limit);

  for (let contact of pageObj.data) {
    const row = jQuery("<tr/>");

    row.append(jQuery("<td/>").text(contact.id));
    row.append(jQuery("<td/>").text(contact.name));
    row.append(jQuery("<td/>").text(contact.email));
    row.append(jQuery("<td/>").text(contact.phone));
    row.append(jQuery("<td/>").text(contact.address));

    tableBody.append(row);
  }

  if (totalPages !== page + 1) {
    next_button.prop("disabled", false);
  }

  if (page !== 0) {
    prev_button.prop("disabled", false);
  }

  messageEls.hide();
  clearInterval(animateText);

  if (pageObj.data.length === 0) {
    messageEls.text("No Data");
    messageEls.show();
  }
}

(async function () {
  limit = await getSetting("table_limit");
  jQuery(".cm-contacts-mgr.table-loading-message").height(44 * limit);

  bg_color = await getSetting("background_color");
  jQuery(".contacts-mgr-box .table").css("background-color", bg_color);

  const allTables = jQuery(".contacts-mgr-box table.table-hover");
  orderby = await getSetting("table_order_by");
  renderTable(allTables, 0, limit, orderby);

  jQuery(".prev-button").on("click", function () {
    const table = jQuery(this).parent().siblings("table.table-hover");
    const page_no = jQuery(this).siblings(".page-number").text() - 1;
    if (page_no > 0) renderTable(table, page_no - 1, limit, orderby);
  });

  jQuery(".next-button").on("click", function () {
    const table = jQuery(this).parent().siblings("table.table-hover");
    const page_no = jQuery(this).siblings(".page-number").text() - 1;
    renderTable(table, page_no + 1, limit, orderby);
  });
})();
