$(document).ready(function () {
  $("#busqueda").keypress(function (e) {
    if (e.which == 13) {
      buscar("busqueda", "calidad");
      return false; //<---- Add this line
    }
  });

  filtro();
   
});

function filtro() {
  
    // filter functions
    var filterFns = {
      // show if name ends with -ium
      ium: function() {
      var name = $(this).find('.name').text();
      return name.match(/ium$/);
      }
    };

  // init Isotope
  var $grid = $('.grid');

  var $filterButtonGroup = $('.filter-button-group');
  // bind filter button click
  $filterButtonGroup.on('click', 'a', function() {
      var filterAttr = $(this).attr('data-filter');
      // set filter in hash
      location.hash = 'filter=' + encodeURIComponent(filterAttr);
      console.log("lol");
  });
  
  var isIsotopeInit = false;
  

  $(window).on('hashchange', onHashchange);

  // trigger event handler to init Isotope
  onHashchange();

      $grid.isotope('layout');
      window.onload = function() {
          $grid.isotope('layout');
      };

  function onHashchange() {
      var hashFilter = getHashFilter();
      if (!hashFilter && isIsotopeInit) {
      return;
      }
      isIsotopeInit = true;
      // filter isotope
      $grid.isotope({
      itemSelector: '.element-item',
      layoutMode: 'fitRows',
      // use filterFns
      filter: filterFns[hashFilter] || hashFilter
      });
      $grid.isotope('layout');
      // set selected class on button
      if (hashFilter) {
      $filterButtonGroup.find('.is-checked').removeClass('is-checked');
      $filterButtonGroup.find('[data-filter="' + hashFilter + '"]').addClass('is-checked');
      }
  }

  function getHashFilter() {
      // get filter=filterName
      var matches = location.hash.match(/filter=([^&]+)/i);
      var hashFilter = matches && matches[1];
      return hashFilter && decodeURIComponent(hashFilter);
  }
}

function buscar(_busqueda, _calidad, _tipo) {
  var _strTexto;
  var _nCalidad;
  var _formEnviado = 1;

  if (_tipo == "2") {
    //Funcion a partir de dos string titulo y calidad
    _strTexto = _busqueda;
    _nCalidad = _calidad;
    $("#busqueda").val(_strTexto);
    $("#calidad").val(_nCalidad);
  } else {
    _strTexto = $("#" + _busqueda).val();
    _nCalidad = $("#" + _calidad).val();
  }

  $(".progress-bar").css("width", "0%");
  $(".progress-bar").text("0%");
  $(".progress-bar").attr("data-progress", "0");

  //BUSQUEDAS
  $.ajax({
    type: "GET",
    url: "app/feed.php",
    data: {
      q: _strTexto,
      c: _nCalidad,
      form: _formEnviado,
    },
    dataType: "html",
    beforeSend: function () {
      $("#listado").css("opacity", "0.5");

      $("#cargador,.progress").show();
      var _porcentaje = 10;
      var id = setInterval(frame, 10);
      function frame() {
        if (_porcentaje >= 95) {
          clearInterval(id);
        } else {
          _porcentaje++;
          $(".progress-bar").css("width", _porcentaje + "%");
          $(".progress-bar").text(_porcentaje + "%");
          $(".progress-bar").attr("aria-valuenow", _porcentaje);
        }
      }
    },
    success: function (response) {
      $("#listado").css("opacity", "1");

      $("#cargador,.progress").show();
      $(".progress-bar").css("width", "100%");
      $(".progress-bar").text("100%");
      $(".progress-bar").attr("aria-valuenow", "100");

      $("#listado").html(response);
    },
    complete: function () {
      $("#cargador,.progress").fadeOut();
    },
  });

  if (_tipo != "2") {
    //GUARDA BUSQUEDAS
    $.ajax({
      type: "POST",
      url: "app/publico/busquedas.php",
      data: {
        q: _strTexto,
        c: _nCalidad,
        tipo: "add",
      },
      dataType: "html",
      beforeSend: function () {
        $("#cargador").show();
      },
      success: function (response) {
        $("#listaBusquedas").html(response);
      },
      complete: function () {},
      error: function (result) {},
    });
  }
}

function guardarSerie(_busqueda, _tipo) {
  _serie = $("#" + _busqueda).val();

  if (!_tipo) {
    _tipo = "add";
  }

  $.ajax({
    type: "POST",
    url: "app/publico/series.php",
    data: {
      serie: _serie,
      tipo: _tipo,
    },
    dataType: "html",
    beforeSend: function () {
      $("#cargador").show();
    },
    success: function (response) {
      $("#cargador").show();

      $("#listaSeries").html(response);
    },
    complete: function () {
      $("#cargador").hide();
    },
  });
}

function guardarCapitulo(_hash, _estado) {
  $.ajax({
    type: "POST",
    url: "app/publico/capitulos.php",
    data: {
      hash: _hash,
      estado: _estado,
    },
    dataType: "html",
    beforeSend: function () {
      $("#cargador").show();
    },
    success: function (response) {
      $("#cargador").show();

      buscar("busqueda", "calidad", 3);
      guardarSerie("busqueda", "reload");
    },
    complete: function () {
      $("#cargador").hide();
    },
  });
}

function guardarCapituloEnSerie(_idSerie, _hashCapitulo) {
  $.ajax({
    type: "POST",
    url: "app/publico/seriesActivas.php",
    data: {
      idSerie: _idSerie,
      idCapitulo: _hashCapitulo,
    },
    dataType: "html",
    beforeSend: function () {
      $("#cargador").show();
    },
    success: function (response) {
      $("#cargador").show();

      $("#listaSeries").html(response);
      buscar("busqueda", "calidad", 3);
    },
    complete: function () {
      $("#cargador").hide();
    },
  });
}

function allowDrop(ev) {
  // console.log("allowDrop");
  ev.preventDefault();
  // $("#listaSeries li .drop").removeClass("activo");
  // var idSerie = ev.target.id;
  // $("#" + idSerie).addClass("activo");
}

function drag(ev) {
  // console.log("drag");
  $("#carrito").addClass("open");
  ev.dataTransfer.setData("text", ev.target.id);
  // $("#listaSeries li").addClass("activo");
}

function dragEnd(ev) {
  // console.log("dragEnd");
  $("#carrito").removeClass("open");
  // $("#listaSeries li,#listaSeries li .drop").removeClass("activo");
}

function drop(ev) {
  // console.log("drop");
  ev.preventDefault();

  // ev.target.appendChild(document.getElementById(data));

  var hash = ev.dataTransfer.getData("text");
  hashJQ = $("#" + hash);
  hash = hashJQ.data("hash");
  var dragzone = ev.target.id;
  dragzone = $("#" + dragzone).data("id");

  // guardarCapituloEnSerie(idSerie, hash);

  // console.log(hash);
  // console.log(dragzone);

  ev.dataTransfer.clearData();

  items = $("#" + dragzone + " .card").length;
  // console.log(items);
  var cant = 7;
  if (!hashJQ.hasClass("encarrito")) {
    hashJQ.addClass("encarrito");
    if (items <= cant) listarCarrito(hash);
    if (items == cant) $("#btnComprar").show();
  }
}

// $("#addposter").on("submit", function (e) {
function addPoster() {
  // var data = $("#addposter").serialize();
  var _code = $("#code").val();
  var _stock = $("#stock").val();
  var _type = $('input[name="type"]:checked').val();
  var _category = $("#category").val();
  var _imagen = $("#addImagenPoster").prop("files")[0];

  var form_data = new FormData();
  form_data.append("file", _imagen);
  form_data.append("code", _code);
  form_data.append("stock", _stock);
  form_data.append("type", _type);
  form_data.append("category", _category);

  // console.log(form_data);

  $.ajax({
    type: "POST",
    url: "../app/publico/posters.php",
    dataType: "text",
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    beforeSend: function () {
      // $(".submitBtn").attr("disabled", "disabled");
      $("#addposter").css("opacity", ".5");
      $("#btnAddPoster").prop("disabled", true);
      $("#btnAddPoster span").show();
    },
    success: function (msg) {
      $("#msj").html("");
      if (msg == "ok") {
        $("#addposter")[0].reset();
        $("#msj")
          .show()
          .html(
            '<span style="font-size:18px;color:#34A853">Form data submitted successfully.</span>'
          );
      } else {
        $("#msj")
          .show()
          .html(
            '<span style="font-size:18px;color:#EA4335">Some problem occurred, please try again.</span>'
          );
      }
      $("#addposter").css("opacity", "");
      $("#msj").html(msg);
      // $(".submitBtn").removeAttr("disabled");
    },
    complete: function () {
      $("#btnAddPoster").prop("disabled", false);
      $("#btnAddPoster span").hide();
      $("#addposter")[0].reset();
    },
  });
}
// });

//file type validation
function verificarFormatoImagen(_id) {
  _id = $("#" + _id);

  var file = _id.get(0).files[0];
  var imagefile = file.type;
  var match = ["image/jpeg", "image/png", "image/jpg"];
  if (
    !(imagefile == match[0] || imagefile == match[1] || imagefile == match[2])
  ) {
    $("#msj").show().html("Please select a valid image file (JPEG/JPG/PNG).");
    _id.val("");
    return false;
  }
}

function venderPoster(_idPoster, _multiple = false) {
  $.ajax({
    type: "POST",
    url: "app/publico/posters.php",
    data: {
      idPoster: _idPoster,
      tipo: "venta",
    },
    dataType: "html",
    beforeSend: function () {
      $("#cargador").show();
    },
    success: function (response) {
      $("#cargador").show();
      $("#listado").html(response);
    },
    complete: function () {
      $("#cargador").hide();
      if (!_multiple) $("#ideliminado").val(_idPoster);
      $("#alertaEliminado").show(0);
      $("#alertaEliminado").addClass("fade show");

      $("#alertaEliminado")
        .delay(5000)
        .hide(0, function () {
          $("#alertaEliminado").removeClass("fade show");
        });
    },
  });
}

function venderPosterStock(_idPoster, _multiple = false) {
  $.ajax({
    type: "POST",
    url: "app/publico/posters.php",
    data: {
      idPoster: _idPoster,
      tipo: "venta",
    },
    dataType: "html",
    beforeSend: function () {
      $("#cargador").show();
    },
    success: function (response) {
      $("#cargador").show();
      //("#listado").html(response);
      //filtro();
      $("#listado").isotope()
      .html( response )
      .isotope( 'insert', response )
      .isotope('layout')
      .isotope('destroy');

    },
    complete: function () {      
      $("#cargador").hide();
      if (!_multiple) $("#ideliminado").val(_idPoster);
      $("#alertaEliminado").show(0);
      $("#alertaEliminado").addClass("fade show");

      $("#alertaEliminado")
        .delay(5000)
        .hide(0, function () {
          $("#alertaEliminado").removeClass("fade show");
        });
        $("#listado").isotope('reloadItems').isotope('layout');
        filtro();
    },
  });
}

function deshacerVendido() {
  var _idPoster = $("#ideliminado").val();
  var posters = _idPoster.split(",");
  console.log(_idPoster);

  posters.forEach(function (item) {
    $.ajax({
      type: "POST",
      url: "app/publico/posters.php",
      data: {
        idPoster: item,
        tipo: "deshacer",
      },
      dataType: "html",
      beforeSend: function () {
        $("#cargador").show();
      },
      success: function (response) {
        $("#cargador").show();
        $("#listado").html(response);
        $("#alertaEliminado").removeClass("fade show");
      },
      complete: function () {
        $("#cargador").hide();
      },
    });
  });
  //RESET
  $("#ideliminado").val("");
}

function listarCarrito(_idPoster) {
  var postersCarrito = $("#postersCarrito");
  var posters = postersCarrito.val();
  var separador = ",";

  $.ajax({
    type: "POST",
    url: "app/publico/posters.php",
    data: {
      idPoster: _idPoster,
      tipo: "carrito",
    },
    dataType: "html",
    beforeSend: function () {
      $("#cargador").show();
    },
    success: function (response) {
      $("#cargador").show();
      $("#carrito").append(response);

      //AGREGO UN LISTADO DE IDSTIKERS PARA SABER CUALES SE COMPRAN
      if (posters != "") separador = ",";
      else separador = "";
      posters += separador + _idPoster;
      postersCarrito.val(posters);
    },
    complete: function () {
      $("#cargador").hide();
    },
  });
}

function comprarPosters() {
  var postersCarrito = $("#postersCarrito").val();
  var posters = postersCarrito.split(",");

  console.log(postersCarrito);
  console.log(posters);

  posters.forEach(function (item) {
    venderPoster(item, true);
  });

  $("#ideliminado").val(postersCarrito);

  resetCarrito();
}

function resetCarrito() {
  $("#postersCarrito").val("");
  $("#carrito").removeClass("open");
  $("#carrito .card").remove();
  $("#btnComprar").hide();
}
