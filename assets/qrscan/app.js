var app = new Vue({
  el: '#app',
  data: {
    scanner: null,
    activeCameraId: null,
    cameras: [],
    scans: [],
    responseData: null,
  },
  mounted: function () {
    var self = this;
    self.scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5 });
    self.scanner.addListener('scan', function (content, image) {
      self.scans.unshift({ date: +(Date.now()), content: content });

      // Ensure the scans array does not exceed a length of 10
      if (self.scans.length > 10) {
        self.scans.pop(); // Remove the oldest entry (last element)
      }

      $.ajax({
          url: 'ajax_scan_qr', // Adjust URL as needed
          type: 'POST',
          data: { content: content }, // Send as form data
          success: function(response) {
              console.log('Response from server:', response);
              self.responseData = response;
          },
          error: function(xhr, status, error) {
              console.error('Error occurred:', error);
              self.responseData = error;
          }
      });

    });
    Instascan.Camera.getCameras().then(function (cameras) {
      self.cameras = cameras;
      if (cameras.length > 0) {
        self.activeCameraId = cameras[0].id;
        self.scanner.start(cameras[0]);
      } else {
        console.error('No cameras found.');
      }
    }).catch(function (e) {
      console.error(e);
    });
  },
  methods: {
    formatName: function (name) {
      return name || '(unknown)';
    },
    selectCamera: function (camera) {
      this.activeCameraId = camera.id;
      this.scanner.start(camera);
    }
  }
});
