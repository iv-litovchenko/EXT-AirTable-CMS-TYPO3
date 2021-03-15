// C) Relationship (user function register)
// $rows = NewTable::with('userClients')->get();
public function userClients() {
	return $this->hasMany(Client::class);
    return $this->refProvider('proptblref_clients'); // Rel_1To1, Rel_1ToM, Rel_MTo1...
}
