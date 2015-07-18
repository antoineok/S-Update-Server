<?php

/*
 * Copyright 2015 TheShark34
 *
 * This file is part of S-Update-Server.
 *
 * S-Update-Server is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * S-Update-Server is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with S-Update-Server.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace SUpdateServer;

/**
 * The SessionManager, to check if the user is logged, etc...
 *
 * @author TheShark34
 * @package S-Update-Server
 * @version 3.0.0-BETA
 */
class SessionManager {

    /**
     * The current SessionManager
     */
    private static $sessionManager;

    /**
     * Where the password is located
     */
    private $passwordLocation;

    /**
     * The default password location
     */
    const DEFAULT_PASSWORD_LOCATION = "S-Update-Server/pass.word";

    public function __construct($passwordLocation) {
        $this->passwordLocation = $passwordLocation;
    }

    /**
     * Create the SessionManager and redirect the user
     */
    public static function create() {
        self::$sessionManager = new SessionManager(self::DEFAULT_PASSWORD_LOCATION);
        self::$sessionManager->start();
    }

    /**
     * Loads all, and redirect the user
     */
    private function start() {
        // Starting the session
        session_start();

        // If the user is logged
        if(isset($_SESSION["logged"]) && $_SESSION["logged"] == true)
            // If the current route is 'Login'
            if(\Paladin\Paladin::getRouteLoader()->getCurrentRoute()['name'] == "Login")
                // Redirecting him to the index
                header("Location: " . \Paladin\Paladin::getRootFolder());
            // Else
            else
                // Loading the route, normally
                \Paladin\Paladin::getRouteLoader()->loadRoute();
        // Else
        else
            // If the current route isn't 'Install' or 'Login'
            if(\Paladin\Paladin::getRouteLoader()->getCurrentRoute()['name'] != "Install" && \Paladin\Paladin::getRouteLoader()->getCurrentRoute()['name'] != "Login")
                // Redirecting it
                header("Location: " . \Paladin\Paladin::getRootFolder() . "Login");
            // Else
            else
                // Loading the route, normally
                \Paladin\Paladin::getRouteLoader()->loadRoute();
    }

    /**
     * Check if the given password is the good password
     *
     * @param password
     *            The password to check
     * @return True if the password match, false if not
     */
    public function checkPassword($password) {
        $cryptedPassword = sha1($password);

        if(strtoupper(trim($cryptedPassword)) == strtoupper(trim(file_get_contents($this->passwordLocation))))
            return true;
        return false;
    }

    /**
     * Return the password location
     *
     * @return The password location
     */
    public function getPasswordLocation() {
        return $this->passwordLocation;
    }

    /**
     * Returns the current SessionManager
     *
     * @return The current SessionManager
     */
    public static function getSessionManager() {
        return self::$sessionManager;
    }

}
