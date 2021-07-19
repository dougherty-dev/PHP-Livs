/* jshint esversion: 6 */

(function() { // Livs
	"use strict";

	// ===== nyttiga funktioner =====
	var tom = (s) => (!s || s.length === 0);
	var avrunda = (x, n) => +(Math.round(x + `e+${n}`)  + `e-${n}`);

	// ===== kakor =====
	function sätt_kaka(namn, värde, tid = 365) {
		const d = new Date();
		d.setTime(d.getTime() + (tid * 86400000)); //24 d * 60 h * 60 m * 1000 s
		var utgår = "expires="+d.toUTCString();
		document.cookie = namn + "=" + värde + ";" + utgår + ";path=/";
	}

	function kontrollera_kaka(namn, värde) {
		var kaka = hämta_kaka(namn);
		if (kaka === "") {
			sätt_kaka(namn, värde);
			kaka = värde;
		}
		return kaka;
	}

	function hämta_kaka(namn) {
		namn += "=";
		var kl = document.cookie.split(";");
		var len = kl.length;
		for (var i = 0; i < len; i++) {
			var kaka = kl[i];
			while (kaka.charAt(0) === " ") {
				kaka = kaka.substring(1);
			}
			if (kaka.indexOf(namn) === 0) {
				return kaka.substring(namn.length, kaka.length);
			}
		}
		return "";
	}

	// =====tillståndsvariabler =====
	var fullvidd = kontrollera_kaka("fullvidd", "");
	var fullvy = kontrollera_kaka("fullvy", "");
	var g = kontrollera_kaka("g", "g4");
	var rdi = kontrollera_kaka("rdi", "rdi");

	var dra = () => $("tbody.måldefinition").sortable({items: "> tr.dra"});

	function tabellid_tabell(elem) {
		var tabellid = elem.closest("table").attr("data-id");
		var tabell = "table[data-id=" + tabellid + "] ";
		return [tabellid, tabell];
	}

	initiera();
	function initiera() {
		$("button.fullvy").text(fullvy ? "🔼": "↕️");
		$("button.fullvidd").text(fullvidd ? "◀️": "↔️");

		$("#flikar").tabs();

		rita();
		dra();
		summera_tabeller();
	}

	function rita() {
		kategorier();
		intag();
	}

	näringsrikast();
	function näringsrikast() {
		var sortering = kontrollera_kaka("sortering", "Se");
		var antal = kontrollera_kaka("antal", "100");
		var grupper = kontrollera_kaka("grupper", "");
		var ag = kontrollera_kaka("ag", "");

		if (grupper == "") {
			grupper = hämta_grupper();
			sätt_kaka("grupper", grupper);
		}

		$("#sortering").val(sortering);
		$("#antal").val(antal);
		$("#använd_grupper").prop("checked", ag ? "ag" : "");
		populera_grupper();
		förnya_livmedelstabell(sortering, antal, grupper);
	}

	// ===== grupper =====
	function populera_grupper() {
		var grupper = hämta_kaka("grupper");
		for (let k in JSON.parse(grupper)) {
			$("#" + k).prop("checked", true);
		}
	}

	function hämta_grupper() {
		var grupper = {};
		$("#gruppform input:checked").each(function() {
			grupper[$(this).attr("name")] = $(this).val();
		});
		return JSON.stringify(grupper);
	}

	function förnya_livmedelstabell(sortering, antal, grupper) {
		var ag = $("#använd_grupper").prop("checked") ? "ag" : "";
		$.post(document.location + "/../ajax/sortera_livs.php", {sortering: sortering, antal: antal, grupper: grupper, ag: ag}).done(function(data) {
			$("#livsmedelstabell").replaceWith(data);
			rita();
		});
	}

	$("#använd_grupper").change(function() {
		var ag = $("#använd_grupper").prop("checked") ? "ag" : "";
		var sortering = $("#sortering option:selected").val();
		var antal = $("#antal option:selected").val();
		var grupper = hämta_grupper();
		sätt_kaka("ag", ag);
		förnya_livmedelstabell(sortering, antal, grupper);
	});

	$("fieldset#grupper input").change(function() {
		var ag = $("#använd_grupper").prop("checked") ? "ag" : "";
		var sortering = $("#sortering option:selected").val();
		var antal = $("#antal option:selected").val();
		var grupper = hämta_grupper();
		sätt_kaka("grupper", grupper);
		if (ag == "ag") förnya_livmedelstabell(sortering, antal, grupper);
	});

	$("#sortering, #antal").change(function() {
		var sortering = $("#sortering option:selected").val();
		var antal = $("#antal option:selected").val();
		var grupper = hämta_grupper();
		sätt_kaka("sortering", sortering);
		sätt_kaka("antal", antal);
		$("#sortering, #antal").blur();
		förnya_livmedelstabell(sortering, antal, grupper);
	});

	$("#menyer table").on("click", "caption", function() {
		$(this).parent().find("thead, tbody, tfoot").toggle();
	});

	// ===== menyer =====
	$("#menyer").on("click", ".spara_meny", function(e) {
		var tabellid, tabell;
		[tabellid, tabell] = tabellid_tabell($(this));
		$.post(document.location + "/../ajax/spara_meny.php", $("form[data-id=" + tabellid + "]").serializeArray(), function() {
			window.location.replace("./");
		});
	});

	$("#menyer").on("click", ".radera_meny", function(e) {
		var tabellid, tabell;
		[tabellid, tabell] = tabellid_tabell($(this));
		if (confirm("Radera meny?")) {
			$.post(document.location + "/../ajax/radera_meny.php", {tabellid: tabellid}, function() {
				window.location.replace("./");
			});
		}
	});

	$("#menyer").on("focus", ".meny", function() {
		$(this).next(".spara_meny").prop("disabled", tom($(this).val()));
	});

	$("#menyer").on("keyup", ".meny", function(e) {
		var t = tom($(this).val());
		$(this).next(".spara_meny").prop("disabled", t);
		if (e.which === 13 && !t) {
			$(this).next(".spara_meny").click();
		}
	});

	// ===== mål =====
	$("#menyer").on("click", ".addera_mål", function(e) {
		var tfoot_summering = $(this).closest("tfoot.summering");
		$.post(document.location + "/../ajax/addera_mål.php").done(function(html) {
			tfoot_summering.before(html);
			tfoot_summering.prev("tbody.måldefinition").find("tr td input.mål").first().focus();
			rita();
			dra();
		});
	});

	$("#menyer").on("keyup", ".mål", function(e) {
		if (e.which === 13) {
			var tbody = $(this).closest("tbody.måldefinition");
			tbody.find("tr[data-ingrediens-id] td input.ingrediens").first().focus();
		}
	});

	$("#menyer").on("click", ".radera_mål", function(e) {
		var tabellid, tabell;
		[tabellid, tabell] = tabellid_tabell($(this));
		$(this).closest("tbody.måldefinition").remove();
		summera_tabell(tabellid);
	});

	// ===== ingredienser =====
	$("#menyer").on("click", ".radera_ingrediens", function(e) {
		e.preventDefault();
		var tabellid, tabell;
		[tabellid, tabell] = tabellid_tabell($(this));
		$(this).closest("tr").remove();
		summera_tabell(tabellid);
	});

	$("#menyer").on("change", ".mängd", function() {
		var tr = $(this).closest("tr");
		var id = tr.attr("data-ingrediens-id");
		if (id === 0) return false;

		var mängd = $(this).val();
		var tbody = $(this).closest("tbody");
		var målid = tbody.attr("id");
		var målnamn = tbody.find("input.mål").first().val();
		var tabellid, tabell;
		[tabellid, tabell] = tabellid_tabell(tr);

		$.post(document.location + "/../ajax/livsmedelsdata.php", {målid: målid, målnamn: målnamn, id: id, sista: 0, mängd: mängd}).done(function(data) {
			tr.replaceWith(JSON.parse(data)[1]);
			$("tr.ny.dra").next("tr").focus();
			$(".ny").removeClass("ny");
			summera_tabell(tabellid);
			rita();
		});
	});

	$("#menyer").on("keydown", ".mängd", function(e) {
		if (e.which === 13) {
			var ntr = $(this).closest("tr").next("tr");
			ntr.find("td.nw input.ingrediens").first().focus();
		}
	});

	$("#menyer").on("focusin", "input.ingrediens", function() {
		$(this).autocomplete({
			source: function(request, response) {
				$.post(document.location + "/../ajax/sök.php", {term: request.term}, "json").done(function(data) {
					response(JSON.parse(data));
				});
			},
			select: function(e, ui) {
				var tr = $(this).closest("tr");
				var sista = tr.attr("data-ingrediens-id") ? 0 : 1;
				var mängd = tr.find(".mängd").val();

				var tbody = $(this).closest("tbody");
				var målid = tbody.attr("id");
				var målnamn = tbody.find("input.mål").first().val();

				var tabellid, tabell;
				[tabellid, tabell] = tabellid_tabell(tr);

				$(this).val(ui.item.label);
				tr.attr("data-ingrediens-id", ui.item.value);
				$.post(document.location + "/../ajax/livsmedelsdata.php", {målid: målid, målnamn: målnamn, id: ui.item.value, sista: sista, mängd: mängd}).done(function(data) {
					tr.replaceWith(JSON.parse(data)[1]);
					$("input.ny.mängd").select();
					$(".ny").removeClass("ny");
					summera_tabell(tabellid);
					rita();
				});
				return false;
			},
			focus: function(e, ui) {
				$(this).val(ui.item.label);
				return false;
			},
		});
	});

	// ===== summera ämnen och beräkna procent av behov =====

	function summera_tabeller() { // bara vid initiering
		$("#menyer table").each(function(i) {
			var tabellid = $(this).attr("data-id");
			if (tabellid > 0) summera_tabell(tabellid);
		});
	}

	function summera_tabell(tabellid) {
		var summa, v;
		var table = "table[data-id=" + tabellid + "] ";
		töm_tabell(table);
		$(table + "tr[data-ingrediens-id]:first td.v").each(function(i) { // första raden
				summa = 0;
				$(table + "tr[data-ingrediens-id]").each(function(j) { // iterera över kolumner
					v = parseFloat($("td.v", this).eq(i).text());
					if (!isNaN(v)) summa += v;
				});

				$(table + "tfoot.summering tr.summa td.s").eq(i).text(avrunda(summa, 2));
				summera_kolumn(table + "thead tr.rdi th.rdiv", table + "tfoot.summering tr.procent.rdi td.s", summa, i);
				summera_kolumn(table + "thead tr.li th.liv", table + "tfoot.summering tr.procent.li td.s", summa, i);
				summera_kolumn(table + "thead tr.sb th.sbv", table + "tfoot.summering tr.procent.sb td.s", summa, i);
		});
	}

	function summera_kolumn(intag, resultat, summa, i) {
		var iv = parseFloat($(intag).eq(i).text());
		if (iv > 0) {
			var p = avrunda(100 * summa / iv, 2);
			$(resultat).eq(i).text(p);
			$(resultat).eq(i).toggleClass("fullvärdig", p >= 100);
		}
	}

	function töm_tabell(table) {
		for (let k in ["summa", "procent.li", "procent.sb", "procent.rdi"]) {
			let e = table + "tfoot.summering";
			let f = "tr." + k + " td.s";
			$(e).find(f).removeClass("fullvärdig").text(null);
		}
	}

	// ===== kompakt / full vy =====
	$(".fullvy").on("click", "", function() {
		fullvy = !fullvy;
		$("button.fullvy").text(fullvy ? "🔼": "↕️");
		sätt_kaka("fullvy", fullvy ? "fullvy" : "");
		intag();
	});

	$(".fullvidd").click(function() {
		fullvidd = !fullvidd;
		$("button.fullvidd").text(fullvidd ? "◀️": "↔️");
		sätt_kaka("fullvidd", fullvidd ? "fullvidd" : "");
		kategorier();
	});

	// ===== kategorier =====
	function hs(show, hide, v, typ) {
		$(hide).hide();
		$(show).show();
		if (typ === "g") g = v;
		else if (typ === "rdi") rdi = v;
		sätt_kaka(typ, v);
	}

	$("table").on("click", ".g1", () => fullvidd || hs(".g2", ".g1, .g3, .g4", "g2", "g"));
	$("table").on("click", ".g2", () => fullvidd || hs(".g3", ".g1, .g2, .g4", "g3", "g"));
	$("table").on("click", ".g3", () => fullvidd || hs(".g4", ".g1, .g2, .g3", "g4", "g"));
	$("table").on("click", ".g4", () => fullvidd || hs(".g1", ".g2, .g3, .g4", "g1", "g"));

	$("table").on("click", ".rdi", () => fullvy || hs(".li", ".rdi, .sb", "li", "rdi"));
	$("table").on("click", ".li", () => fullvy || hs(".sb", ".rdi, .li", "sb", "rdi"));
	$("table").on("click", ".sb", () => fullvy || hs(".rdi", ".sb, .li", "rdi", "rdi"));

	function kategorier() {
		if (fullvidd) {
			$(".g1, .g2, .g3, .g4").show();
		} else {
			if (g === "g1") hs(".g1", ".g2, .g3, .g4", "g1", "g");
			else if (g === "g2") hs(".g2", ".g1, .g3, .g4", "g2", "g");
			else if (g === "g3") hs(".g3", ".g1, .g2, .g4", "g3", "g");
			else if (g === "g4") hs(".g4", ".g1, .g2, .g3", "g4", "g");
		}
	}

	function intag() {
		if (fullvy) {
			$(".rdi, .li, .sb").show();
		} else {
			if (rdi === "rdi") hs(".rdi", ".sb, .li", "rdi", "rdi");
			else if (rdi === "sb") hs(".sb", ".rdi, .li", "sb", "rdi");
			else if (rdi === "li") hs(".li", ".rdi, .sb", "li", "rdi");
		}
	}

})(); // Livs
