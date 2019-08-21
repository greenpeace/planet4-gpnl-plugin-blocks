"use strict";$("#electionModal").on("show.bs.modal",function(t){var o=$(t.relatedTarget).data("id"),e=$(this),i="election_object_"+o;e.find(".modal-title").text("Stem op "+window[i].title),e.find(".modal-body").text(window[i].description),console.log()});
//# sourceMappingURL=maps/gpnl-election.js.map
