CREATE TABLE "game" ("game_id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , "game_name" VARCHAR, "game_name_short" VARCHAR);
CREATE TABLE "user_game" ("user_id" INTEGER NOT NULL , "game_id" INTEGER NOT NULL , "comments" TEXT, "ability_crunch" INTEGER, "ability_lore" INTEGER, "ability_gm" INTEGER, "learn" BOOL, PRIMARY KEY ("user_id", "game_id"));
