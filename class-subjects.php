<?php

namespace OMSB;

require_once 'class-list.php';
require_once 'class-database.php';

use OMSB\ListClass;
use OMSB\Database;

class Subjects extends ListClass {

  public $list;

  public $table_name;

  public $db;

  public function __construct() {
    $this->table_name = 'subjects';
    $this->db         = new Database;

    $this->list = [
      "Agriculture",
      "Apocalypticism",
      "Architecture and Buildings",
      "Art",
      "Asiatic Nomads: Huns Mongols, etc.",
      "Byzantium",
      "Carolingians",
      "Church Fathers",
      "Classics / Humanism",
      "Clergy - Anticlericalism",
      "Clergy - Monks Nuns, Friars",
      "Clergy - Priests Bishops, Canons",
      "Conversion",
      "Cosmology",
      "Crusades",
      "Diplomacy",
      "Early Germanic Peoples: Goths Franks, etc.",
      "Economy - Crafts and Industry",
      "Economy - Guilds and Labor",
      "Economy - Trade",
      "Education / Universities",
      "Family / Children",
      "Government",
      "Grammar / Rhetoric",
      "Heresy",
      "Historiography",
      "Jews / Judaism",
      "Law - Canon",
      "Law - Crime",
      "Law - Secular",
      "Literature - Allegory",
      "Literature - Arthurian",
      "Literature - Comedy / Satire",
      "Literature - Devotional",
      "Literatyrd - Didactic",
      "Literature - Drama",
      "Literature - Epics Romance",
      "Literature - Folklore Legends",
      "Literature - Other",
      "Magic / Witchcraft",
      "Maritime",
      "Material Culture: Food Clothing, Household",
      "Medicine",
      "Military Orders",
      "Monasticism",
      "Music",
      "Muslims / Islam",
      "Nobility / Gentry",
      "Papacy",
      "Peasants",
      "Philosophy / Theology",
      "Philisophy - Aristotelian",
      "Philosophy - Ethics / Moral Theology",
      "Philosophy - Logic",
      "Philosophy - Metaphysics",
      "Philosophy - Platonic / Neo-Platonic",
      "Philosophy - Political",
      "Piety",
      "Piety - Confession Penance",
      "Piety - Lay",
      "Piety - Mysticism",
      "Plague and Disease",
      "Political Thought",
      "Poverty / Charity",
      "Recreation",
      "Reform",
      "Religion - Institutional Church",
      "Revolt",
      "Royalty / Monarchs",
      "Saints",
      "Saints - Cults / Relics",
      "Science / Technology",
      "Science - Astronomy",
      "Science - Mathematics",
      "Theology - Christology",
      "Theology - Ecclesiology",
      "Theology - Eschatology",
      "Theology - Heretical",
      "Theology - History",
      "Theology - Mariology",
      "Theology - Moral / Ethics",
      "Theology - Sacramental",
      "Theology - Scriptural / Exegesis",
      "Theology - Trinitarian",
      "Towns / Cities",
      "Travel / Pilgrimage",
      "Vikings",
      "War - Chivalry",
      "War - Military History",
      "Women / Gender"
    ];
  }

}
?>
