/*
 * Copyright (c) 2006-2008 Knut Kohl <knutkohl@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 */

// name = string equal to the name of the instance of the object
// defaultExpiration = number of units to make the default expiration date for the cookie
// expirationUnits = 'seconds' | 'minutes' | 'hours' | 'days' | 'months' | 'years' (default is 'days')
function esf_Cookiemanager(name, defaultExpiration, expirationUnits) {
	// remember our name
	this.name = name;
	// the default cookie prefix handeled by this cookie manager
	this.prefix = 'esf_';
	// get the default expiration
	this.defaultExpiration = this.getExpiration(defaultExpiration,expirationUnits);
	// set the default path
	this.defaultPath = '/';
	// initialize an object to hold all the document's cookies
	this.cookies = new Object();
	// initialize an object to hold expiration dates for the doucment's cookies
	this.expiration = new Object();
	// set an onlunload function to write the cookies
	var unloadfunc = new Function (this.name+'.setDocumentCookies();');
  if (typeof window.onunload == 'function') {
    var oldunloadfunc = window.onunload;
    window.onunload = function () {
      oldunloadfunc();
      unloadfunc();
    };
  } else {
    window.onunload = unloadfunc;
  }
  // hash for unique cookie names
  this.hash = ((ApplicationId) ? ApplicationId : document.domain) + '_';
	// get the document's cookies
  this.getDocumentCookies();
}
// gets an expiration date for a cookie as a GMT string
// expiration = integer expressing time in units (default is 7 days)
// units = 'miliseconds' | 'seconds' | 'minutes' | 'hours' | 'days' | 'months' | 'years' (default is 'days')
esf_Cookiemanager.prototype.getExpiration = function(expiration, units) {
  // if no expiration time wasn't supplied, set to "session life time""
  if (expiration === undefined) {
    return;
  }

	// set default expiration time if it wasn't supplied
	// expiration = (expiration)?expiration:7;
	// supply default units if units weren't supplied
	units = (units) ? units : 'days';
	// new date object we'll use to get the expiration time
	var date = new Date();
	// set expiration time according to units supplied
	switch(units) {
		case 'years':
			date.setFullYear(date.getFullYear() + expiration);
			break;
		case 'months':
			date.setMonth(date.getMonth() + expiration);
			break;
		case 'days':
			date.setTime(date.getTime()+(expiration*24*60*60*1000));
			break;
		case 'hours':
			date.setTime(date.getTime()+(expiration*60*60*1000));
			break;
		case 'minutes':
			date.setTime(date.getTime()+(expiration*60*1000));
			break;
		case 'seconds':
			date.setTime(date.getTime()+(expiration*1000));
			break;
		default:
			date.setTime(date.getTime()+expiration);
			break;
		}
	// return expiration as GMT string
	return date.toGMTString();
};

// gets all document cookies and populates the .cookies property with them
esf_Cookiemanager.prototype.getDocumentCookies = function() {
	var cookie, pair;

//	alert(document.cookie);

	// read the document's cookies into an array
	var cookies = document.cookie.split(';');
	// walk through each array element and extract the name and value into the cookies property
	var len = cookies.length;
	for (var i=0; i<len; i++) {
		cookie = cookies[i];
		// strip leading whitespace
		while (cookie.charAt(0) == ' ') {
      cookie = cookie.substring(1,cookie.length);
    }
		// split name/value pair into an array
		pair = cookie.split('=');
    if (pair[0].substr(0,this.hash.length+this.prefix.length) == this.hash+this.prefix) {
		  // use the cookie name WITHOUT hash as the property name and value as the value
  		this.cookies[pair[0].substr(this.hash.length)] = pair[1];
    }
	}
};

// sets all document cookies
esf_Cookiemanager.prototype.setDocumentCookies = function() {
	var expires, cookies;
	for (var name in this.cookies) {
		// see if there's a custom expiration for this cookie; if not use default
		expires = (this.expiration[name]) ? this.expiration[name] : this.defaultExpiration;
		// add to cookie string
		cookies = this.hash + name + '=' + this.cookies[name] + '; expires=' + expires +
              '; path=' + this.defaultPath + '; domain=' + document.domain;


		document.cookie = cookies;
	}
	return true;
};

// gets cookie value
// cookieName = string, cookie name
esf_Cookiemanager.prototype.getCookie = function(cookieName) {
	var cookie = this.cookies[cookieName];
  switch (cookie) {
    // translate to boolean value
    case 'true':  return true;
    case 'false': return false;
    default:      return cookie;  // also "undefined"
  }
};

// stores cookie value, expiration, domain and path
// cookieName = string, cookie name
// cookieValue = string, cookie value
// expiration = number of units in which the cookie should expire
// expirationUnits = 'miliseconds' | 'seconds' | 'minutes' | 'hours' | 'days' | 'months' | 'years' (default is 'days')
esf_Cookiemanager.prototype.setCookie = function(cookieName,cookieValue,expiration,expirationUnits) {
	this.cookies[cookieName] = cookieValue;
	// set the expiration if it was supplied
	if (expiration) {
    this.expiration[cookieName] = this.getExpiration(expiration,expirationUnits);
  }
	return true;
};
