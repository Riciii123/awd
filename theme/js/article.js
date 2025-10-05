tinymce.init({
    selector: "#page-content",
    height: 400,
    language: "sk",
    menubar: false,
    entity_encoding: "raw",
    convert_urls : false,
    plugins: "textcolor link autolink paste lists advlist image table hr code",
    paste_as_text: false,
    toolbar: [
      "undo redo | pastetext | bold italic forecolor backcolor | formatselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink | image",
      "table tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol | hr | code | removeformat",
    ],
  });