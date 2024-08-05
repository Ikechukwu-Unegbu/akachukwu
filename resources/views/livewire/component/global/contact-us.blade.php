<!-- Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="contactModalLabel">Contact Support</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body p-8">
    <p>If you need assistance, you can contact our support team:</p>
    <div class="contact-info">
        <div class="contact-item">
            <i class="fas fa-envelope"></i>
            <span>Email: <a href="mailto:support@vastel.com">{{$settings->email}}</a></span>
        </div>
        <div class="contact-item">
            <i class="fas fa-phone"></i>
            <span>Phone: <a href="">{{$settings->phone1}}</a></span>
        </div>
        <div class="contact-item">
            <i class="fas fa-map-marker-alt"></i>
            <span>{{$settings->address_one}}</span>
        </div>
    </div>
</div>

      <div class="modal-footer">
        <button type="button" class="btn btn-warning vastel-bg" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
