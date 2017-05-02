//save members in global js variable, session caching is done at the endpoint
$(function (){
	$('a[data-toggle="tab"][href="#accept"]').on('shown.bs.tab', function (e) {
		$.ajax({
			type: 'GET',
			url: 'ajax/contracts.php',
			success: function(data){
				console.log('Contracts',data);
				$.each(data,function(i, item){
					var flag = 0;
					$("table#cont-table > tbody > tr > td:eq(0)").each(function(){
						if($(this).text() == item["@attributes"].contractID){
							flag = 1;
						}
					});
					if(flag==0){
						var row = $("table#cont-table > tbody").append("<tr id="+item["@attributes"].contractID+"><td>"+item["@attributes"].contractID+"</td><td>"+mem[item["@attributes"].assigneeID]+"</td><td>"+item["@attributes"].dateIssued+"</td><td>"+item["@attributes"].status+"</td></tr>")
						row.children('#'+item["@attributes"].contractID).click(function () {
							$.ajax({
								type: 'GET',
								url: 'ajax/contract-form.php',
								data: {"contid":item["@attributes"].contractID,"station":item["@attributes"].startStationID, "assignee":item["@attributes"].assigneeID},
								success: function(data){
									$("table#cont-table").hide(350);
									var form=$('#accept').append(data).find('div#contrfrm > form');
									form.submit(function(e) {
										e.preventDefault();
										var rec = $(this).find('#reciever').val();
										var subj = $(this).find('#subject').val();
										var text=$(this).find('#intro-text').val()+$(this).find('#optional-text').val()+$(this).find('end-text').val();
										console.log(text);
										// $.ajax({
										// 	method: 'POST',
										// 	url: 'form/accept.php',
										// 	data: ,
										// 	success: function(data){
										// 		console.log(data);
										// 	}
										// })
									})
								}
							})
						})
					}
				})
			}
		})
	})
})