
  $(document).ready(function() {
    $('.btn-secondary[data-dismiss="modal"]').on('click', function() {
      $('#bookingModal').modal('hide');
    });
  });

  $(document).ready(function () {
    $('.bookBtn').click(function () {
      let eventId = $(this).data('id');
      let eventName = $(this).data('name');
      let templeName = $(this).data('temple');
      let amount = $(this).data('amount');
      let slots = parseInt($(this).data('slots'));

      $('#event_id').val(eventId);
      $('#event_name').val(eventName);
      $('#slots_available').val(slots);
      $('#total_amount').val('');
      $('#people').val('');
      $('#slotAlert').addClass('d-none');

      $('#bookingModal').modal('show');

      $('#people').on('input', function () {
        let peopleCount = parseInt($(this).val());
        if (peopleCount > slots) {
          $('#slotAlert').removeClass('d-none');
          $('#total_amount').val('');
        } else {
          $('#slotAlert').addClass('d-none');
          $('#total_amount').val(peopleCount * amount);
        }
      });
    });

    $('.btn-secondary[data-dismiss="modal"]').on('click', function () {
      $('#bookingModal').modal('hide');
    });
  });

  function checkSlotLimit() {
    const count = parseInt($('#people').val()) || 0;
    const available = parseInt($('#slots_available').val()) || 0;
    if (count > available) {
      $('#slotAlert').removeClass('d-none');
      return false;
    }
    return true;
  }