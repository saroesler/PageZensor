function ResetConfirm(id)
{
	var params = new Object();
	params['id'] = id;
	if(confirm("Achtung, alle von der Redaktion nicht bestätigten Änderungen gehen verloren!!!"))
	{
		window.location = "index.php?module=PageZensor&type=admin&func=ResetConfirm&pid="+id;
	}
}
