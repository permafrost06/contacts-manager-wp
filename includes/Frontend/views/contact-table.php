<div class="contacts-mgr-box contacts-table">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Address</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($all_contacts as $row) { ?>
        <tr>
          <?php foreach ($row as $field) { ?>
            <td><?php esc_html_e($field) ?></td>
          <?php } ?>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>