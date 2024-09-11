<?php

/*
$data = "AA==AA==AA==AA==";
echo base64_encode($data);
echo "<br><br>";
*/

?>

<!DOCTYPE html>
<html>
<head>
<script src="https://api.mapy.cz/loader.js"></script>
<script>Loader.load()</script>
</head>

<body>


<script>

/*
var data = atob("aGVsbG8gd29ybGQ=");
document.write(data);
*/

var alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
var indexed_alphabet = alphabet.split('');
associated_alphabet = {};

for(var i=0; i<alphabet.length; i++) {
	associated_alphabet[alphabet.charAt(i)] = i;
};


//atob
function ____app_decode(data) {
	/* pouzite optimalizace:
	 * - while namisto for
	 * - cachovani Alphabet v ramci redukce tecek
	 */
	var output = [];
	var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
	var input = data.replace(/\s/g,"").split("");
	
	var len = input.length;
	var i=0;
	var A = associated_alphabet;

	while (i < len) {
		enc1 = A[input[i]];
		enc2 = A[input[i+1]];
		enc3 = A[input[i+2]];
		enc4 = A[input[i+3]];

		chr1 = (enc1 << 2) | (enc2 >> 4);
		chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
		chr3 = ((enc3 & 3) << 6) | enc4;

		output.push(chr1);
		if (enc3 != 64) { output.push(chr2); }
		if (enc4 != 64) { output.push(chr3); }
		
		i += 4;
	}

	return output;
	
}


//btoa
function ____app_encode(data) {
	var output = [];

	var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
	var i=0;
	var len = data.length;
	do {
		chr1 = (i < data.length ? data[i++] : NaN);
		chr2 = (i < data.length ? data[i++] : NaN);
		chr3 = (i < data.length ? data[i++] : NaN);

		enc1 = chr1 >> 2;
		enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
		enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
		enc4 = chr3 & 63;

		if (isNaN(chr2)) { 
			enc3 = enc4 = 64;
		} else if (isNaN(chr3)) {
			enc4 = 64;
		}

		output.push(indexed_alphabet[enc1]);
		output.push(indexed_alphabet[enc2]);
		output.push(indexed_alphabet[enc3]);
		output.push(indexed_alphabet[enc4]);

	} while (i < len);

	return output.join("");
}





TYPE_MAGIC		= 25;
TYPE_CALL		= 13;
TYPE_RESPONSE	= 14;
TYPE_FAULT		= 15;

TYPE_INT		= 1;
TYPE_BOOL		= 2;
TYPE_DOUBLE	= 3;
TYPE_STRING	= 4;
TYPE_DATETIME	= 5;
TYPE_BINARY	= 6;
TYPE_INT8P		= 7;
TYPE_INT8N		= 8;
TYPE_STRUCT	= 10;
TYPE_ARRAY		= 11;
TYPE_NULL		= 12;

_hints = null;
_path = [];
_data = [];
_pointer = 0;




// parse
function ____app_parse(data) {
	this._pointer = 0;
	this._data = data;

	var magic1 = this._getByte();
	var magic2 = this._getByte();

	if (magic1 != 0xCA || magic2 != 0x11) {
		this._data = [];
		throw new Error("Missing FRPC magic");
	}

	/* zahodit zbytek hlavicky */
	this._getByte();
	this._getByte();

	var first = this._getInt(1);
	var type = first >> 3;
	if (type == TYPE_FAULT) {
		var num = this._parseValue();
		var msg = this._parseValue();
		this._data = [];
		throw new Error("FRPC/"+num+": "+msg);
	}

	var result = null;

	switch (type) {
		case TYPE_RESPONSE:
			result = this._parseValue();
			if (this._pointer < this._data.length) {
				this._data = [];
				throw new Error("Garbage after FRPC data");
			}
		break;

		case TYPE_CALL:
			var nameLength = this._getInt(1);
			var name = this._decodeUTF8(nameLength);
			var params = [];
			while (this._pointer < this._data.length) { params.push(this._parseValue()); }
			this._data = [];
			return {method:name, params:params};
		break;

		default:
			this._data = [];
			throw new Error("Unsupported FRPC type "+type);
		break;
	}

	this._data = [];
	return result;
}


function _parseValue() {
	/* pouzite optimalizace:
	 * - zkracena cesta ke konstantam v ramci redukce tecek
	 * - posun nejpouzivanejsich typu nahoru
	 */
	var first = this._getInt(1);
	var type = first >> 3;
	switch (type) {
		case this.TYPE_STRING:
			var lengthBytes = (first & 7) + 1;
			var length = this._getInt(lengthBytes);
			return this._decodeUTF8(length);
		break;

		case this.TYPE_STRUCT:
			var result = {};
			var lengthBytes = (first & 7) + 1;
			var members = this._getInt(lengthBytes);
			while (members--) { this._parseMember(result); }
			return result;
		break;

		case this.TYPE_ARRAY:
			var result = [];
			var lengthBytes = (first & 7) + 1;
			var members = this._getInt(lengthBytes);
			while (members--) { result.push(this._parseValue()); }
			return result;
		break;

		case this.TYPE_BOOL:
			return (first & 1 ? true : false);
		break;

		case this.TYPE_INT:
			var length = first & 7;
			var max = Math.pow(2, 8*length);
			var result = this._getInt(length);
			if (result >= max/2) { result -= max; }
			return result;
		break;

		case this.TYPE_DATETIME:
			this._getByte();
			var ts = this._getInt(4);
			for (var i=0;i<5;i++) { this._getByte(); }
			return new Date(1000*ts);
		break;

		case this.TYPE_DOUBLE:
			return this._getDouble();
		break;

		case this.TYPE_BINARY:
			var lengthBytes = (first & 7) + 1;
			var length = this._getInt(lengthBytes);
			var result = [];
			while (length--) { result.push(this._getByte()); }
			return result;
		break;

		case this.TYPE_INT8P:
			var length = (first & 7) + 1;
			return this._getInt(length);
		break;

		case this.TYPE_INT8N:
			var length = (first & 7) + 1;
			return -this._getInt(length);
		break;

		case this.TYPE_NULL:
			return null;
		break;

		default:
			throw new Error("Unkown FRPC type " + type);
		break;
	}
}


function _parseMember(result) {
var nameLength = this._getInt(1);
	var name = this._decodeUTF8(nameLength);
	result[name] = this._parseValue();	
}


function _getInt(bytes) {
var result = 0;
	var factor = 1;

	for (var i=0;i<bytes;i++) {
		result += factor * this._getByte();
		factor *= 256;
	}

	return result;	
}

function _getByte() {
	if ((this._pointer + 1) > this._data.length) { throw new Error("Cannot read byte from buffer"); }
	return this._data[this._pointer++];
}


function _decodeUTF8(length) {
	/* pouzite optimalizace:
	 * - pracujeme nad stringem namisto pole; FF i IE to kupodivu (!) maji rychlejsi
	 * - while namisto for
	 * - cachovani fromCharcode, this._data i this._pointer
	 * - vyhozeni this._getByte
	 */
	var remain = length;
	var result = "";
	if (!length) { return result; }

	var c = 0, c1 = 0, c2 = 0;
	var SfCC = String.fromCharCode;
	var data = this._data;
	var pointer = this._pointer;

	while (1) {
		remain--;
		c = data[pointer];
		pointer += 1;  /* FIXME safari bug */
		if (c < 128) {
			result += SfCC(c);
		} else if ((c > 191) && (c < 224)) {
			c1 = data[pointer];
			pointer += 1; /* FIXME safari bug */
			result += SfCC(((c & 31) << 6) | (c1 & 63));
			remain -= 1;
		} else if (c < 240) {
			c1 = data[pointer++];
			c2 = data[pointer++];
			result += SfCC(((c & 15) << 12) | ((c1 & 63) << 6) | (c2 & 63));
			remain -= 2;
		} else if (c < 248) { /* 4 byte stuff */
			c1 = data[pointer++] & 63;
			c2 = data[pointer++] & 63;
			c3 = data[pointer++] & 63;
			var cp = ((c & 0x07) << 0x12) | (c1 << 0x0C) | (c2 << 0x06) | c3;

			if (cp > 0xFFFF) { /* surrogates */
				cp -= 0x10000;
				result += SfCC((cp >>> 10) & 0x3FF | 0xD800);
				cp = cp & 0x3FF | 0xDC00;
			}
			result += SfCC(cp);
			remain -= 3;
		} else if (c < 252) { /* 5 byte stuff, throw away */
			pointer += 4;
			remain -= 4;
		} else { /* 6 byte stuff, throw away */
			pointer += 5;
			remain -= 5;
		}

		/* pokud bylo na vstupu nevalidni UTF-8, mohli jsme podlezt... */
		if (remain <= 0) { break; }
	}

	/* normalne je v tuto chvili remain = 0; pokud byla ale na vstupu chyba, mohlo klesnout pod nulu. vratime pointer na spravny konec stringu */
	this._pointer = pointer + remain;

	return result;
}


function _getDouble() {
	var bytes = [];
	var index = 8;
	while (index--) { bytes[index] = this._getByte(); }

	var sign = (bytes[0] & 0x80 ? 1 : 0);

	var exponent = (bytes[0] & 127) << 4;
	exponent += bytes[1] >> 4;

	if (exponent == 0) { return Math.pow(-1, sign) * 0; }

	var mantissa = 0;
	var byteIndex = 1;
	var bitIndex = 3;
	var index = 1;

	do {
		var bitValue = (bytes[byteIndex] & (1 << bitIndex) ? 1 : 0);
		mantissa += bitValue * Math.pow(2, -index);

		index++;
		bitIndex--;
		if (bitIndex < 0) {
			bitIndex = 7;
			byteIndex++;
		}
	} while (byteIndex < bytes.length);

	if (exponent == 0x7ff) {
		if (mantissa) {
			return NaN;
		} else {
			return Math.pow(-1, sign) * Infinity;
		}
	}

	exponent -= (1 << 10) - 1;
	return Math.pow(-1, sign) * Math.pow(2, exponent) * (1+mantissa);
}




//document.write(____app_decode("QUE9PUFBPT1BQT09QUE9PQ="));
//document.write(____app_encode("hello world"));

document.write(JSON.stringify(____app_parse(____app_decode("yhECAXBYAlADBnN0YXR1czjIBnJlc3VsdFBRHG9wZW5pbmdfdGltZV9hZGRpdGlvbmFsX2luZm8gGERsZSB0ZWxlZm9uaWNrw6kgZG9tbHV2eRB6Ym96aV9wcmVtaXNlX2lkOrLdAQ16Ym96aV90aW1lb3V0EA1pbXBvcnRlZF9kYXRhYA50aXRsZV9hZGRpdGlvbiAADWFjdHVhbF9vZmZlcnNYAAthZGRyX3N0cmVldCATVSB2b2tvdmlja8OpIMWha29seQdhZGRyZXNzIDJVIHZva292aWNrw6kgxaFrb2x5IDI5OS80LCAxNjAgMDAgIFByYWhhLCBWb2tvdmljZQpzdWJqZWN0X2ljOgexDwltYXBfaWNvbnNQBAVzbWFsbCAraHR0cHM6Ly9hcGkubWFweS5jei9wb2lpbWcvaWNvbi80MDA/c2NhbGU9MQNiaWcgK2h0dHBzOi8vYXBpLm1hcHkuY3ovcG9paW1nL2ljb24vNDAwP3NjYWxlPTMKaGFzRmF2aWNvbhAGbWVkaXVtICtodHRwczovL2FwaS5tYXB5LmN6L3BvaWltZy9pY29uLzQwMD9zY2FsZT0yFGFkZHJfYWRkaXRpb25hbF9pbmZvIAAUb3BlbmluZ190aW1lX3Zpc2libGURD29wZW5pbmdfdGltZV9zdFgHUAcNcmVsYXRpdmVfc29ydDgGDGV4Y2VwdGlvbl9pZCAAA2RheSAGbW9uZGF5EHNwZWNpZmljYXRpb25faWQgEXBob25lX2FwcG9pbnRtZW50BGRhdGUgCjIwMjEtMTAtMDQPYWRkaXRpb25hbF9pbmZvIBhEbGUgdGVsZWZvbmlja8OpIGRvbWx1dnkHbm9uc3RvcBBQCA1yZWxhdGl2ZV9zb3J0OAAMZXhjZXB0aW9uX2lkIAAMaG9saWRheV9pbmZvID9EbmVzIGplIHN0w6F0bsOtIHN2w6F0ZWssIG90ZXbDrXJhY8OtIGhvZGlueSBzZSBtxa/Fvm91IGxpxaFpdC4Qc3BlY2lmaWNhdGlvbl9pZCARcGhvbmVfYXBwb2ludG1lbnQEZGF0ZSAKMjAyMS0wOS0yOA9hZGRpdGlvbmFsX2luZm8gGERsZSB0ZWxlZm9uaWNrw6kgZG9tbHV2eQNkYXkgB3R1ZXNkYXkHbm9uc3RvcBBQBw1yZWxhdGl2ZV9zb3J0OAEMZXhjZXB0aW9uX2lkIAADZGF5IAl3ZWRuZXNkYXkQc3BlY2lmaWNhdGlvbl9pZCARcGhvbmVfYXBwb2ludG1lbnQEZGF0ZSAKMjAyMS0wOS0yOQ9hZGRpdGlvbmFsX2luZm8gGERsZSB0ZWxlZm9uaWNrw6kgZG9tbHV2eQdub25zdG9wEFAHDXJlbGF0aXZlX3NvcnQ4AgxleGNlcHRpb25faWQgAANkYXkgCHRodXJzZGF5EHNwZWNpZmljYXRpb25faWQgEXBob25lX2FwcG9pbnRtZW50BGRhdGUgCjIwMjEtMDktMzAPYWRkaXRpb25hbF9pbmZvIBhEbGUgdGVsZWZvbmlja8OpIGRvbWx1dnkHbm9uc3RvcBBQBw1yZWxhdGl2ZV9zb3J0OAMMZXhjZXB0aW9uX2lkIAADZGF5IAZmcmlkYXkQc3BlY2lmaWNhdGlvbl9pZCARcGhvbmVfYXBwb2ludG1lbnQEZGF0ZSAKMjAyMS0xMC0wMQ9hZGRpdGlvbmFsX2luZm8gGERsZSB0ZWxlZm9uaWNrw6kgZG9tbHV2eQdub25zdG9wEFAHDXJlbGF0aXZlX3NvcnQ4BAxleGNlcHRpb25faWQgAANkYXkgCHNhdHVyZGF5EHNwZWNpZmljYXRpb25faWQgEXBob25lX2FwcG9pbnRtZW50BGRhdGUgCjIwMjEtMTAtMDIPYWRkaXRpb25hbF9pbmZvIBhEbGUgdGVsZWZvbmlja8OpIGRvbWx1dnkHbm9uc3RvcBBQBw1yZWxhdGl2ZV9zb3J0OAUMZXhjZXB0aW9uX2lkIAADZGF5IAZzdW5kYXkQc3BlY2lmaWNhdGlvbl9pZCARcGhvbmVfYXBwb2ludG1lbnQEZGF0ZSAKMjAyMS0xMC0wMw9hZGRpdGlvbmFsX2luZm8gGERsZSB0ZWxlZm9uaWNrw6kgZG9tbHV2eQdub25zdG9wEBF0aXRsZV9hbHRlcm5hdGl2ZSAIaVN3YXAuY3oVdGl0bGVfYWx0ZXJuYXRpdmVfd2ViIAAUc3ViamVjdF9pY19mb3JtYXR0ZWQgCDAxMDI4MzU5AmlkOt9jxg5hdWN0aW9uX3RhcmlmZlAcC29mZmVyX2NvdW50OAANYWN0aW9uX2J1dHRvbhAKaW1hZ2VfbG9nbxAVaW1hZ2VfZXh0ZXJuYWxfcmVtb3ZlEBJpbWFnZV9jbGllbnRfY291bnQ4ABJmYXZpY29uX2ljb25fZm9yY2UQEnRhZ19idXNpbmVzc19jb3VudDgFAmlkIARmcmVlA2VuZGAMYWR2ZXJ0X2NvdW50OAALcGhvbmVfY291bnQ4AQVzdGFydGAJdXJsX2NvdW50OAEUc29jaWFsX25ldHdvcmtfY291bnQ4AA9zdWJqZWN0X21pbl9iaWQ4AAtlbWFpbF9jb3VudDgBCmpvYl9vZmZlcnMQDmNhdGVnb3J5X2NvdW50OAEMZmF2aWNvbl9pY29uEA5pbWFnZV9icmFuZGluZxAQcHJpb3JpdHlfcmVxdWVzdBATcmVjb21tZW5kZWRfcHJlbWlzZRALaW1hZ2VfdmlkZW8QDm1hbnVhbF9hdWN0aW9uEAxwcmVtaXNlX3BhaWQQDmJvb2tpbmdfY3VzdG9tEA9zaW1pbGFyX3ByZW1pc2URBWhwYm94EAltYXJrZXJfaWQgBmRldGFpbBRyZWdpb25fZGlzdHJpY3RfbmFtZSAHUHJhaGEgNgh1cmxzX2FsbFgBUAUDdXJsIFVodHRwOi8vd3d3Lmlzd2FwLmN6I3V0bV9zb3VyY2U9ZmlybXkuY3omdXRtX21lZGl1bT1wcGQmdXRtX2NhbXBhaWduPWZpcm15LmN6LTEzMDAxNjk1BG1haW4RBHR5cGUgA1dlYgdiYXNlVXJsIBNodHRwOi8vd3d3Lmlzd2FwLmN6CnZpc2libGVVcmwgE2h0dHA6Ly93d3cuaXN3YXAuY3oMcmV2aWV3X2NvdW50OAAFdGl0bGUgCkpha3ViIFppbWEIcGFydG5lcnNYAApzdWJqZWN0X2lkOk7mOglsb2dvc191cmxYAAlzZW9fdGl0bGUgCGlzd2FwLWN6CnBob3Rvc191cmxYAAdidXR0b25zWAAJcGFyZW50X2lkOAALcHJlbWlzZXNfaWRYAAdjdXJkYXRlKADgPlJhAgDAszQFc3RhcnMYAAAAAAAA8L8MZ3BzX2xhdGl0dWRlGAAAAMBhDElADG9wZW5pbmdfdGltZVgHIAUwLCwsLCAFMCwsLCwgBTAsLCwsIAUwLCwsLCAFMCwsLCwgBTAsLCwsIAUwLCwsLA5jYW5fbGlzdF9ncm91cBAZb3BlbmluZ190aW1lX2hvbGlkYXlfaW5mbyA/RG5lcyBqZSBzdMOhdG7DrSBzdsOhdGVrLCBvdGV2w61yYWPDrSBob2Rpbnkgc2UgbcWvxb5vdSBsacWhaXQuC2FkZHJfcG9fYm94IAAQY2FuX2hhdmVfcmVxdWVzdBEVaGFzX2RlbGl2ZXJ5X2xvY2F0aW9uERZza2xpa19yZXRhcmdldGluZ19jb2RlIAU5NDQ1ORBtYXBfcGljdHVyZV90eXBlIAdtYXBpY29uCmFwZXRlZV91cmwgAAR0YWdzWANQAwhzZW9fbmFtZSANcGxhdGJhLWthcnRvdQR0eXBlIAl0ZWNobmljYWwEbmFtZSANUGxhdGJhIGthcnRvdVADCHNlb19uYW1lIAVlc2hvcAR0eXBlIAhpbnRlcm5hbARuYW1lIAZFLXNob3BQAwhzZW9fbmFtZSAIcHJvZGVqbmEEdHlwZSAIaW50ZXJuYWwEbmFtZSAKUHJvdm96b3ZuYQphbm9ueW1pemVkEAdnYWxsZXJ5WAJQDw9pc1dlYlNjcmVlbnNob3QRBGZ1bGwgR2h0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfZ1lfZy9iVGlCZUMucG5nP2ZsPXJlcyw2MDAsNDAwLDMsZmZmZmZmC2Rlc2NyaXB0aW9uUAEEdHlwZSAKc2NyZWVuc2hvdAtoaW50R2FsbGVyeSBBaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19nWV9nL2JUaUJlQy5wbmc/Zmw9cmVzLDEyMDAsOTAwLDEDYmlnIC9odHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX2dZX2cvYlRpQmVDLnBuZwRoYWxmIEdodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX2dZX2cvYlRpQmVDLnBuZz9mbD1yZXMsMzAwLDIwMCwzLGZmZmZmZgRsaXN0IEdodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX2dZX2cvYlRpQmVDLnBuZz9mbD1yZXMsMjIwLDE0NywzLGZmZmZmZgltYXBjaXJjbGUgRWh0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfZ1lfZy9iVGlCZUMucG5nP2ZsPXJlcyw1OSw1OSwzLGZmZmZmZg9oaW50VGh1bWJNZWRpdW0gQGh0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfZ1lfZy9iVGlCZUMucG5nP2ZsPXJlcyw2MDAsNDAwLDMFc21hbGwgRmh0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfZ1lfZy9iVGlCZUMucG5nP2ZsPXJlcywxMTIsODQsMSxmZmZmZmYGbWVkaXVtIEdodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX2dZX2cvYlRpQmVDLnBuZz9mbD1yZXMsNjAwLDQ1MCwxLGZmZmZmZghub2JvcmRlciBHaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19nWV9nL2JUaUJlQy5wbmc/Zmw9cmVzLDYwMCw0NTAsMyxmZmZmZmYOaGludFRodW1iU21hbGwgQGh0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfZ1lfZy9iVGlCZUMucG5nP2ZsPXJlcywzMDAsMjAwLDMHZ2FsbGVyeSBHaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19nWV9nL2JUaUJlQy5wbmc/Zmw9cmVzLDI1MCwxNjcsMyxmZmZmZmYEb3JpZyAvaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19nWV9nL2JUaUJlQy5wbmdQDgRmdWxsIEdodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX0hfSC9seFlzYnYuanBlZz9mbD1yZXMsNjAwLDQwMCwzLGZmZmZmZgtkZXNjcmlwdGlvblAEA3BpZDuy0ScEA3lhdxj9R5aR10bkPwd3ZWJfdXJsIHBodHRwczovL3d3dy5tYXB5LmN6L3pha2xhZG5pP3Bhbm89MSZwaWQ9Njk3MTg0NTAmeWF3PTAuNjMzNjQ4JmZvdj0xLjU3JnBpdGNoPS0wLjA2NiZ6PTE3JnNvdXJjZT1maXJtJmlkPTEzMDAxNjk1BHR5cGUgCHBhbm9yYW1hC2hpbnRHYWxsZXJ5IEFodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX0hfSC9seFlzYnYuanBlZz9mbD1yZXMsMTIwMCw5MDAsMQNiaWcgL2h0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfSF9IL2x4WXNidi5qcGVnBGhhbGYgR2h0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfSF9IL2x4WXNidi5qcGVnP2ZsPXJlcywzMDAsMjAwLDMsZmZmZmZmBGxpc3QgR2h0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfSF9IL2x4WXNidi5qcGVnP2ZsPXJlcywyMjAsMTQ3LDMsZmZmZmZmCW1hcGNpcmNsZSBFaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19IX0gvbHhZc2J2LmpwZWc/Zmw9cmVzLDU5LDU5LDMsZmZmZmZmD2hpbnRUaHVtYk1lZGl1bSBAaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19IX0gvbHhZc2J2LmpwZWc/Zmw9cmVzLDYwMCw0MDAsMwVzbWFsbCBGaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19IX0gvbHhZc2J2LmpwZWc/Zmw9cmVzLDExMiw4NCwxLGZmZmZmZgZtZWRpdW0gR2h0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfSF9IL2x4WXNidi5qcGVnP2ZsPXJlcyw2MDAsNDUwLDEsZmZmZmZmCG5vYm9yZGVyIEdodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX0hfSC9seFlzYnYuanBlZz9mbD1yZXMsNjAwLDQ1MCwzLGZmZmZmZg5oaW50VGh1bWJTbWFsbCBAaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19IX0gvbHhZc2J2LmpwZWc/Zmw9cmVzLDMwMCwyMDAsMwdnYWxsZXJ5IEdodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX0hfSC9seFlzYnYuanBlZz9mbD1yZXMsMjUwLDE2NywzLGZmZmZmZgRvcmlnIC9odHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX0hfSC9seFlzYnYuanBlZwpncHNfZW5jb2RlIAo5Z3AzdnhYLjUtD21hcF9waWN0dXJlX3VybCAraHR0cHM6Ly9hcGkubWFweS5jei9wb2lpbWcvaWNvbi80MDA/c2NhbGU9MQl0aXRsZV93ZWIgCGlTd2FwLmN6FmRlbGl2ZXJ5X25ldHdvcmtzX2RhdGFQABJwaG90b3NfcHJlbWlzZV91cmxYAVAPD2lzV2ViU2NyZWVuc2hvdBEEZnVsbCBHaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19nWV9nL2JUaUJlQy5wbmc/Zmw9cmVzLDYwMCw0MDAsMyxmZmZmZmYLZGVzY3JpcHRpb25QAQR0eXBlIApzY3JlZW5zaG90C2hpbnRHYWxsZXJ5IEFodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX2dZX2cvYlRpQmVDLnBuZz9mbD1yZXMsMTIwMCw5MDAsMQNiaWcgL2h0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfZ1lfZy9iVGlCZUMucG5nBGhhbGYgR2h0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfZ1lfZy9iVGlCZUMucG5nP2ZsPXJlcywzMDAsMjAwLDMsZmZmZmZmBGxpc3QgR2h0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfZ1lfZy9iVGlCZUMucG5nP2ZsPXJlcywyMjAsMTQ3LDMsZmZmZmZmCW1hcGNpcmNsZSBFaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19nWV9nL2JUaUJlQy5wbmc/Zmw9cmVzLDU5LDU5LDMsZmZmZmZmD2hpbnRUaHVtYk1lZGl1bSBAaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19nWV9nL2JUaUJlQy5wbmc/Zmw9cmVzLDYwMCw0MDAsMwVzbWFsbCBGaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19nWV9nL2JUaUJlQy5wbmc/Zmw9cmVzLDExMiw4NCwxLGZmZmZmZgZtZWRpdW0gR2h0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfZ1lfZy9iVGlCZUMucG5nP2ZsPXJlcyw2MDAsNDUwLDEsZmZmZmZmCG5vYm9yZGVyIEdodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX2dZX2cvYlRpQmVDLnBuZz9mbD1yZXMsNjAwLDQ1MCwzLGZmZmZmZg5oaW50VGh1bWJTbWFsbCBAaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19nWV9nL2JUaUJlQy5wbmc/Zmw9cmVzLDMwMCwyMDAsMwdnYWxsZXJ5IEdodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX2dZX2cvYlRpQmVDLnBuZz9mbD1yZXMsMjUwLDE2NywzLGZmZmZmZgRvcmlnIC9odHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX2dZX2cvYlRpQmVDLnBuZw9tYWluX29mZmVyX3R5cGUgAAtmaWx0ZXJzX3Nlb1gBIA1wbGF0YmEta2FydG91GWF2ZXJhZ2VfcmV2aWV3X3BlcmNlbnRhZ2UYAAAAAAAA8L8QaGFzX29wZW5pbmdfdGltZRAGb2ZmZXJzWAAWb3BlbmluZ190aW1lX25leHRfd2Vla1gHUAcNcmVsYXRpdmVfc29ydDgGDGV4Y2VwdGlvbl9pZCAAA2RheSAGbW9uZGF5EHNwZWNpZmljYXRpb25faWQgEXBob25lX2FwcG9pbnRtZW50BGRhdGUgCjIwMjEtMTAtMTEPYWRkaXRpb25hbF9pbmZvIBhEbGUgdGVsZWZvbmlja8OpIGRvbWx1dnkHbm9uc3RvcBBQBw1yZWxhdGl2ZV9zb3J0OAAMZXhjZXB0aW9uX2lkIAADZGF5IAd0dWVzZGF5EHNwZWNpZmljYXRpb25faWQgEXBob25lX2FwcG9pbnRtZW50BGRhdGUgCjIwMjEtMTAtMDUPYWRkaXRpb25hbF9pbmZvIBhEbGUgdGVsZWZvbmlja8OpIGRvbWx1dnkHbm9uc3RvcBBQBw1yZWxhdGl2ZV9zb3J0OAEMZXhjZXB0aW9uX2lkIAADZGF5IAl3ZWRuZXNkYXkQc3BlY2lmaWNhdGlvbl9pZCARcGhvbmVfYXBwb2ludG1lbnQEZGF0ZSAKMjAyMS0xMC0wNg9hZGRpdGlvbmFsX2luZm8gGERsZSB0ZWxlZm9uaWNrw6kgZG9tbHV2eQdub25zdG9wEFAHDXJlbGF0aXZlX3NvcnQ4AgxleGNlcHRpb25faWQgAANkYXkgCHRodXJzZGF5EHNwZWNpZmljYXRpb25faWQgEXBob25lX2FwcG9pbnRtZW50BGRhdGUgCjIwMjEtMTAtMDcPYWRkaXRpb25hbF9pbmZvIBhEbGUgdGVsZWZvbmlja8OpIGRvbWx1dnkHbm9uc3RvcBBQBw1yZWxhdGl2ZV9zb3J0OAMMZXhjZXB0aW9uX2lkIAADZGF5IAZmcmlkYXkQc3BlY2lmaWNhdGlvbl9pZCARcGhvbmVfYXBwb2ludG1lbnQEZGF0ZSAKMjAyMS0xMC0wOA9hZGRpdGlvbmFsX2luZm8gGERsZSB0ZWxlZm9uaWNrw6kgZG9tbHV2eQdub25zdG9wEFAHDXJlbGF0aXZlX3NvcnQ4BAxleGNlcHRpb25faWQgAANkYXkgCHNhdHVyZGF5EHNwZWNpZmljYXRpb25faWQgEXBob25lX2FwcG9pbnRtZW50BGRhdGUgCjIwMjEtMTAtMDkPYWRkaXRpb25hbF9pbmZvIBhEbGUgdGVsZWZvbmlja8OpIGRvbWx1dnkHbm9uc3RvcBBQBw1yZWxhdGl2ZV9zb3J0OAUMZXhjZXB0aW9uX2lkIAADZGF5IAZzdW5kYXkQc3BlY2lmaWNhdGlvbl9pZCARcGhvbmVfYXBwb2ludG1lbnQEZGF0ZSAKMjAyMS0xMC0xMA9hZGRpdGlvbmFsX2luZm8gGERsZSB0ZWxlZm9uaWNrw6kgZG9tbHV2eQdub25zdG9wEBFpc19wYWlkX29yX25vbmNvbRAJYWRkcl9jaXR5IA9QcmFoYSwgVm9rb3ZpY2UOc29jaWFsX25ldHdvcmtYAA1ncHNfbG9uZ2l0dWRlGAAAAIAasSxACnBob25lc19vYmpYAVADBHJvbGUgBU1vYmlsBm51bWJlciAJNzczMTE4NDcyDGNvdW50cnlfY29kZSADNDIwD2NhdGVnb3J5V2VpZ2h0c1gAEnNob3dfYnVzaW5lc3NfaW5mbxEKZW1haWxzX29ialgBUAIEcm9sZSAGRS1tYWlsBWVtYWlsIA1pbmZvQGlzd2FwLmN6C3VybF92aXNpYmxlIBNodHRwOi8vd3d3Lmlzd2FwLmN6B2lzX3BhaWQQFXRpdGxlX3VzZV9hbHRlcm5hdGl2ZREHZW5hYmxlZBERYWxsb3dlZF90ZW1wbGF0ZXNYASAHZGVmYXVsdAVlbWFpbCAOaXN3YXBAZW1haWwuY3oLZGVzY3JpcHRpb24gWVByb2RlaiBuw6FocmFkbsOtY2ggZMOtbMWvIHBybyBBcHBsZSB6YcWZw616ZW7DrS4gVsO9a3VwIHBvxaFrb3plbsO9Y2ggQXBwbGUgemHFmcOtemVuw60uCGFkZHJfemlwIAUxNjAwMAhzZW9fbmFtZSAXaXN3YXAtY3otcHJhaGEtdm9rb3ZpY2UOY2F0ZWdvcmllc19vYmpYAVAGBG5hbWUgHUVsZWt0cm8sIG1vYmlseSBhIHBvxI3DrXRhxI1lCXBhcmVudF9pZDgACW5hbWVfcGF0aCAdRWxla3RybywgbW9iaWx5IGEgcG/EjcOtdGHEjWUEcGF0aCAZRWxla3Ryby1tb2JpbHktYS1wb2NpdGFjZQdpZF9wYXRoIAMzNzECaWQ5cwELc3ViamVjdF9kaWMgABhvcGVuaW5nX3RpbWVfZGVzY3JpcHRpb24gEk90ZXbDrXJhY8OtIGhvZGlueQN1cmwgVWh0dHA6Ly93d3cuaXN3YXAuY3ojdXRtX3NvdXJjZT1maXJteS5jeiZ1dG1fbWVkaXVtPXBwZCZ1dG1fY2FtcGFpZ249ZmlybXkuY3otMTMwMDE2OTUMZmlsdGVyc19uYW1lWAEgDVBsYXRiYSBrYXJ0b3UIZ3JvdXBfaWQ632PGDmFkZHJfaG91c2VfbnVtIAUyOTkvNA1zdGF0dXNNZXNzYWdlIAJPS1ADBnN0YXR1czjIBnJlc3VsdFAFB3Jldmlld3NYABNyZXZpZXdDb3VudFdlaWdodGVkGAAAAAAAAAAADHRvdGFsTWF0Y2hlczgAEXJldmlld3NQZXJjZW50YWdlGAAAAAAAAPC/DHJldmlld3NTdGFycxgAAAAAAADwvw1zdGF0dXNNZXNzYWdlIAJPSw=="))));


/*
document.write(____app_decode("yhECAXBYAlADBnN0YXR1czjIBnJlc3VsdFBRHG9wZW5pbmdfdGltZV9hZGRpdGlvbmFsX2luZm8gGERsZSB0ZWxlZm9uaWNrw6kgZG9tbHV2eRB6Ym96aV9wcmVtaXNlX2lkOrLdAQ16Ym96aV90aW1lb3V0EA1pbXBvcnRlZF9kYXRhYA50aXRsZV9hZGRpdGlvbiAADWFjdHVhbF9vZmZlcnNYAAthZGRyX3N0cmVldCATVSB2b2tvdmlja8OpIMWha29seQdhZGRyZXNzIDJVIHZva292aWNrw6kgxaFrb2x5IDI5OS80LCAxNjAgMDAgIFByYWhhLCBWb2tvdmljZQpzdWJqZWN0X2ljOgexDwltYXBfaWNvbnNQBAVzbWFsbCAraHR0cHM6Ly9hcGkubWFweS5jei9wb2lpbWcvaWNvbi80MDA/c2NhbGU9MQNiaWcgK2h0dHBzOi8vYXBpLm1hcHkuY3ovcG9paW1nL2ljb24vNDAwP3NjYWxlPTMKaGFzRmF2aWNvbhAGbWVkaXVtICtodHRwczovL2FwaS5tYXB5LmN6L3BvaWltZy9pY29uLzQwMD9zY2FsZT0yFGFkZHJfYWRkaXRpb25hbF9pbmZvIAAUb3BlbmluZ190aW1lX3Zpc2libGURD29wZW5pbmdfdGltZV9zdFgHUAcNcmVsYXRpdmVfc29ydDgGDGV4Y2VwdGlvbl9pZCAAA2RheSAGbW9uZGF5EHNwZWNpZmljYXRpb25faWQgEXBob25lX2FwcG9pbnRtZW50BGRhdGUgCjIwMjEtMTAtMDQPYWRkaXRpb25hbF9pbmZvIBhEbGUgdGVsZWZvbmlja8OpIGRvbWx1dnkHbm9uc3RvcBBQCA1yZWxhdGl2ZV9zb3J0OAAMZXhjZXB0aW9uX2lkIAAMaG9saWRheV9pbmZvID9EbmVzIGplIHN0w6F0bsOtIHN2w6F0ZWssIG90ZXbDrXJhY8OtIGhvZGlueSBzZSBtxa/Fvm91IGxpxaFpdC4Qc3BlY2lmaWNhdGlvbl9pZCARcGhvbmVfYXBwb2ludG1lbnQEZGF0ZSAKMjAyMS0wOS0yOA9hZGRpdGlvbmFsX2luZm8gGERsZSB0ZWxlZm9uaWNrw6kgZG9tbHV2eQNkYXkgB3R1ZXNkYXkHbm9uc3RvcBBQBw1yZWxhdGl2ZV9zb3J0OAEMZXhjZXB0aW9uX2lkIAADZGF5IAl3ZWRuZXNkYXkQc3BlY2lmaWNhdGlvbl9pZCARcGhvbmVfYXBwb2ludG1lbnQEZGF0ZSAKMjAyMS0wOS0yOQ9hZGRpdGlvbmFsX2luZm8gGERsZSB0ZWxlZm9uaWNrw6kgZG9tbHV2eQdub25zdG9wEFAHDXJlbGF0aXZlX3NvcnQ4AgxleGNlcHRpb25faWQgAANkYXkgCHRodXJzZGF5EHNwZWNpZmljYXRpb25faWQgEXBob25lX2FwcG9pbnRtZW50BGRhdGUgCjIwMjEtMDktMzAPYWRkaXRpb25hbF9pbmZvIBhEbGUgdGVsZWZvbmlja8OpIGRvbWx1dnkHbm9uc3RvcBBQBw1yZWxhdGl2ZV9zb3J0OAMMZXhjZXB0aW9uX2lkIAADZGF5IAZmcmlkYXkQc3BlY2lmaWNhdGlvbl9pZCARcGhvbmVfYXBwb2ludG1lbnQEZGF0ZSAKMjAyMS0xMC0wMQ9hZGRpdGlvbmFsX2luZm8gGERsZSB0ZWxlZm9uaWNrw6kgZG9tbHV2eQdub25zdG9wEFAHDXJlbGF0aXZlX3NvcnQ4BAxleGNlcHRpb25faWQgAANkYXkgCHNhdHVyZGF5EHNwZWNpZmljYXRpb25faWQgEXBob25lX2FwcG9pbnRtZW50BGRhdGUgCjIwMjEtMTAtMDIPYWRkaXRpb25hbF9pbmZvIBhEbGUgdGVsZWZvbmlja8OpIGRvbWx1dnkHbm9uc3RvcBBQBw1yZWxhdGl2ZV9zb3J0OAUMZXhjZXB0aW9uX2lkIAADZGF5IAZzdW5kYXkQc3BlY2lmaWNhdGlvbl9pZCARcGhvbmVfYXBwb2ludG1lbnQEZGF0ZSAKMjAyMS0xMC0wMw9hZGRpdGlvbmFsX2luZm8gGERsZSB0ZWxlZm9uaWNrw6kgZG9tbHV2eQdub25zdG9wEBF0aXRsZV9hbHRlcm5hdGl2ZSAIaVN3YXAuY3oVdGl0bGVfYWx0ZXJuYXRpdmVfd2ViIAAUc3ViamVjdF9pY19mb3JtYXR0ZWQgCDAxMDI4MzU5AmlkOt9jxg5hdWN0aW9uX3RhcmlmZlAcC29mZmVyX2NvdW50OAANYWN0aW9uX2J1dHRvbhAKaW1hZ2VfbG9nbxAVaW1hZ2VfZXh0ZXJuYWxfcmVtb3ZlEBJpbWFnZV9jbGllbnRfY291bnQ4ABJmYXZpY29uX2ljb25fZm9yY2UQEnRhZ19idXNpbmVzc19jb3VudDgFAmlkIARmcmVlA2VuZGAMYWR2ZXJ0X2NvdW50OAALcGhvbmVfY291bnQ4AQVzdGFydGAJdXJsX2NvdW50OAEUc29jaWFsX25ldHdvcmtfY291bnQ4AA9zdWJqZWN0X21pbl9iaWQ4AAtlbWFpbF9jb3VudDgBCmpvYl9vZmZlcnMQDmNhdGVnb3J5X2NvdW50OAEMZmF2aWNvbl9pY29uEA5pbWFnZV9icmFuZGluZxAQcHJpb3JpdHlfcmVxdWVzdBATcmVjb21tZW5kZWRfcHJlbWlzZRALaW1hZ2VfdmlkZW8QDm1hbnVhbF9hdWN0aW9uEAxwcmVtaXNlX3BhaWQQDmJvb2tpbmdfY3VzdG9tEA9zaW1pbGFyX3ByZW1pc2URBWhwYm94EAltYXJrZXJfaWQgBmRldGFpbBRyZWdpb25fZGlzdHJpY3RfbmFtZSAHUHJhaGEgNgh1cmxzX2FsbFgBUAUDdXJsIFVodHRwOi8vd3d3Lmlzd2FwLmN6I3V0bV9zb3VyY2U9ZmlybXkuY3omdXRtX21lZGl1bT1wcGQmdXRtX2NhbXBhaWduPWZpcm15LmN6LTEzMDAxNjk1BG1haW4RBHR5cGUgA1dlYgdiYXNlVXJsIBNodHRwOi8vd3d3Lmlzd2FwLmN6CnZpc2libGVVcmwgE2h0dHA6Ly93d3cuaXN3YXAuY3oMcmV2aWV3X2NvdW50OAAFdGl0bGUgCkpha3ViIFppbWEIcGFydG5lcnNYAApzdWJqZWN0X2lkOk7mOglsb2dvc191cmxYAAlzZW9fdGl0bGUgCGlzd2FwLWN6CnBob3Rvc191cmxYAAdidXR0b25zWAAJcGFyZW50X2lkOAALcHJlbWlzZXNfaWRYAAdjdXJkYXRlKADgPlJhAgDAszQFc3RhcnMYAAAAAAAA8L8MZ3BzX2xhdGl0dWRlGAAAAMBhDElADG9wZW5pbmdfdGltZVgHIAUwLCwsLCAFMCwsLCwgBTAsLCwsIAUwLCwsLCAFMCwsLCwgBTAsLCwsIAUwLCwsLA5jYW5fbGlzdF9ncm91cBAZb3BlbmluZ190aW1lX2hvbGlkYXlfaW5mbyA/RG5lcyBqZSBzdMOhdG7DrSBzdsOhdGVrLCBvdGV2w61yYWPDrSBob2Rpbnkgc2UgbcWvxb5vdSBsacWhaXQuC2FkZHJfcG9fYm94IAAQY2FuX2hhdmVfcmVxdWVzdBEVaGFzX2RlbGl2ZXJ5X2xvY2F0aW9uERZza2xpa19yZXRhcmdldGluZ19jb2RlIAU5NDQ1ORBtYXBfcGljdHVyZV90eXBlIAdtYXBpY29uCmFwZXRlZV91cmwgAAR0YWdzWANQAwhzZW9fbmFtZSANcGxhdGJhLWthcnRvdQR0eXBlIAl0ZWNobmljYWwEbmFtZSANUGxhdGJhIGthcnRvdVADCHNlb19uYW1lIAVlc2hvcAR0eXBlIAhpbnRlcm5hbARuYW1lIAZFLXNob3BQAwhzZW9fbmFtZSAIcHJvZGVqbmEEdHlwZSAIaW50ZXJuYWwEbmFtZSAKUHJvdm96b3ZuYQphbm9ueW1pemVkEAdnYWxsZXJ5WAJQDw9pc1dlYlNjcmVlbnNob3QRBGZ1bGwgR2h0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfZ1lfZy9iVGlCZUMucG5nP2ZsPXJlcyw2MDAsNDAwLDMsZmZmZmZmC2Rlc2NyaXB0aW9uUAEEdHlwZSAKc2NyZWVuc2hvdAtoaW50R2FsbGVyeSBBaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19nWV9nL2JUaUJlQy5wbmc/Zmw9cmVzLDEyMDAsOTAwLDEDYmlnIC9odHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX2dZX2cvYlRpQmVDLnBuZwRoYWxmIEdodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX2dZX2cvYlRpQmVDLnBuZz9mbD1yZXMsMzAwLDIwMCwzLGZmZmZmZgRsaXN0IEdodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX2dZX2cvYlRpQmVDLnBuZz9mbD1yZXMsMjIwLDE0NywzLGZmZmZmZgltYXBjaXJjbGUgRWh0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfZ1lfZy9iVGlCZUMucG5nP2ZsPXJlcyw1OSw1OSwzLGZmZmZmZg9oaW50VGh1bWJNZWRpdW0gQGh0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfZ1lfZy9iVGlCZUMucG5nP2ZsPXJlcyw2MDAsNDAwLDMFc21hbGwgRmh0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfZ1lfZy9iVGlCZUMucG5nP2ZsPXJlcywxMTIsODQsMSxmZmZmZmYGbWVkaXVtIEdodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX2dZX2cvYlRpQmVDLnBuZz9mbD1yZXMsNjAwLDQ1MCwxLGZmZmZmZghub2JvcmRlciBHaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19nWV9nL2JUaUJlQy5wbmc/Zmw9cmVzLDYwMCw0NTAsMyxmZmZmZmYOaGludFRodW1iU21hbGwgQGh0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfZ1lfZy9iVGlCZUMucG5nP2ZsPXJlcywzMDAsMjAwLDMHZ2FsbGVyeSBHaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19nWV9nL2JUaUJlQy5wbmc/Zmw9cmVzLDI1MCwxNjcsMyxmZmZmZmYEb3JpZyAvaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19nWV9nL2JUaUJlQy5wbmdQDgRmdWxsIEdodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX0hfSC9seFlzYnYuanBlZz9mbD1yZXMsNjAwLDQwMCwzLGZmZmZmZgtkZXNjcmlwdGlvblAEA3BpZDuy0ScEA3lhdxj9R5aR10bkPwd3ZWJfdXJsIHBodHRwczovL3d3dy5tYXB5LmN6L3pha2xhZG5pP3Bhbm89MSZwaWQ9Njk3MTg0NTAmeWF3PTAuNjMzNjQ4JmZvdj0xLjU3JnBpdGNoPS0wLjA2NiZ6PTE3JnNvdXJjZT1maXJtJmlkPTEzMDAxNjk1BHR5cGUgCHBhbm9yYW1hC2hpbnRHYWxsZXJ5IEFodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX0hfSC9seFlzYnYuanBlZz9mbD1yZXMsMTIwMCw5MDAsMQNiaWcgL2h0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfSF9IL2x4WXNidi5qcGVnBGhhbGYgR2h0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfSF9IL2x4WXNidi5qcGVnP2ZsPXJlcywzMDAsMjAwLDMsZmZmZmZmBGxpc3QgR2h0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfSF9IL2x4WXNidi5qcGVnP2ZsPXJlcywyMjAsMTQ3LDMsZmZmZmZmCW1hcGNpcmNsZSBFaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19IX0gvbHhZc2J2LmpwZWc/Zmw9cmVzLDU5LDU5LDMsZmZmZmZmD2hpbnRUaHVtYk1lZGl1bSBAaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19IX0gvbHhZc2J2LmpwZWc/Zmw9cmVzLDYwMCw0MDAsMwVzbWFsbCBGaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19IX0gvbHhZc2J2LmpwZWc/Zmw9cmVzLDExMiw4NCwxLGZmZmZmZgZtZWRpdW0gR2h0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfSF9IL2x4WXNidi5qcGVnP2ZsPXJlcyw2MDAsNDUwLDEsZmZmZmZmCG5vYm9yZGVyIEdodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX0hfSC9seFlzYnYuanBlZz9mbD1yZXMsNjAwLDQ1MCwzLGZmZmZmZg5oaW50VGh1bWJTbWFsbCBAaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19IX0gvbHhZc2J2LmpwZWc/Zmw9cmVzLDMwMCwyMDAsMwdnYWxsZXJ5IEdodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX0hfSC9seFlzYnYuanBlZz9mbD1yZXMsMjUwLDE2NywzLGZmZmZmZgRvcmlnIC9odHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX0hfSC9seFlzYnYuanBlZwpncHNfZW5jb2RlIAo5Z3AzdnhYLjUtD21hcF9waWN0dXJlX3VybCAraHR0cHM6Ly9hcGkubWFweS5jei9wb2lpbWcvaWNvbi80MDA/c2NhbGU9MQl0aXRsZV93ZWIgCGlTd2FwLmN6FmRlbGl2ZXJ5X25ldHdvcmtzX2RhdGFQABJwaG90b3NfcHJlbWlzZV91cmxYAVAPD2lzV2ViU2NyZWVuc2hvdBEEZnVsbCBHaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19nWV9nL2JUaUJlQy5wbmc/Zmw9cmVzLDYwMCw0MDAsMyxmZmZmZmYLZGVzY3JpcHRpb25QAQR0eXBlIApzY3JlZW5zaG90C2hpbnRHYWxsZXJ5IEFodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX2dZX2cvYlRpQmVDLnBuZz9mbD1yZXMsMTIwMCw5MDAsMQNiaWcgL2h0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfZ1lfZy9iVGlCZUMucG5nBGhhbGYgR2h0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfZ1lfZy9iVGlCZUMucG5nP2ZsPXJlcywzMDAsMjAwLDMsZmZmZmZmBGxpc3QgR2h0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfZ1lfZy9iVGlCZUMucG5nP2ZsPXJlcywyMjAsMTQ3LDMsZmZmZmZmCW1hcGNpcmNsZSBFaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19nWV9nL2JUaUJlQy5wbmc/Zmw9cmVzLDU5LDU5LDMsZmZmZmZmD2hpbnRUaHVtYk1lZGl1bSBAaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19nWV9nL2JUaUJlQy5wbmc/Zmw9cmVzLDYwMCw0MDAsMwVzbWFsbCBGaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19nWV9nL2JUaUJlQy5wbmc/Zmw9cmVzLDExMiw4NCwxLGZmZmZmZgZtZWRpdW0gR2h0dHBzOi8vZDQ4LWEuc2RuLmN6L2RfNDgvY19pbWdfZ1lfZy9iVGlCZUMucG5nP2ZsPXJlcyw2MDAsNDUwLDEsZmZmZmZmCG5vYm9yZGVyIEdodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX2dZX2cvYlRpQmVDLnBuZz9mbD1yZXMsNjAwLDQ1MCwzLGZmZmZmZg5oaW50VGh1bWJTbWFsbCBAaHR0cHM6Ly9kNDgtYS5zZG4uY3ovZF80OC9jX2ltZ19nWV9nL2JUaUJlQy5wbmc/Zmw9cmVzLDMwMCwyMDAsMwdnYWxsZXJ5IEdodHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX2dZX2cvYlRpQmVDLnBuZz9mbD1yZXMsMjUwLDE2NywzLGZmZmZmZgRvcmlnIC9odHRwczovL2Q0OC1hLnNkbi5jei9kXzQ4L2NfaW1nX2dZX2cvYlRpQmVDLnBuZw9tYWluX29mZmVyX3R5cGUgAAtmaWx0ZXJzX3Nlb1gBIA1wbGF0YmEta2FydG91GWF2ZXJhZ2VfcmV2aWV3X3BlcmNlbnRhZ2UYAAAAAAAA8L8QaGFzX29wZW5pbmdfdGltZRAGb2ZmZXJzWAAWb3BlbmluZ190aW1lX25leHRfd2Vla1gHUAcNcmVsYXRpdmVfc29ydDgGDGV4Y2VwdGlvbl9pZCAAA2RheSAGbW9uZGF5EHNwZWNpZmljYXRpb25faWQgEXBob25lX2FwcG9pbnRtZW50BGRhdGUgCjIwMjEtMTAtMTEPYWRkaXRpb25hbF9pbmZvIBhEbGUgdGVsZWZvbmlja8OpIGRvbWx1dnkHbm9uc3RvcBBQBw1yZWxhdGl2ZV9zb3J0OAAMZXhjZXB0aW9uX2lkIAADZGF5IAd0dWVzZGF5EHNwZWNpZmljYXRpb25faWQgEXBob25lX2FwcG9pbnRtZW50BGRhdGUgCjIwMjEtMTAtMDUPYWRkaXRpb25hbF9pbmZvIBhEbGUgdGVsZWZvbmlja8OpIGRvbWx1dnkHbm9uc3RvcBBQBw1yZWxhdGl2ZV9zb3J0OAEMZXhjZXB0aW9uX2lkIAADZGF5IAl3ZWRuZXNkYXkQc3BlY2lmaWNhdGlvbl9pZCARcGhvbmVfYXBwb2ludG1lbnQEZGF0ZSAKMjAyMS0xMC0wNg9hZGRpdGlvbmFsX2luZm8gGERsZSB0ZWxlZm9uaWNrw6kgZG9tbHV2eQdub25zdG9wEFAHDXJlbGF0aXZlX3NvcnQ4AgxleGNlcHRpb25faWQgAANkYXkgCHRodXJzZGF5EHNwZWNpZmljYXRpb25faWQgEXBob25lX2FwcG9pbnRtZW50BGRhdGUgCjIwMjEtMTAtMDcPYWRkaXRpb25hbF9pbmZvIBhEbGUgdGVsZWZvbmlja8OpIGRvbWx1dnkHbm9uc3RvcBBQBw1yZWxhdGl2ZV9zb3J0OAMMZXhjZXB0aW9uX2lkIAADZGF5IAZmcmlkYXkQc3BlY2lmaWNhdGlvbl9pZCARcGhvbmVfYXBwb2ludG1lbnQEZGF0ZSAKMjAyMS0xMC0wOA9hZGRpdGlvbmFsX2luZm8gGERsZSB0ZWxlZm9uaWNrw6kgZG9tbHV2eQdub25zdG9wEFAHDXJlbGF0aXZlX3NvcnQ4BAxleGNlcHRpb25faWQgAANkYXkgCHNhdHVyZGF5EHNwZWNpZmljYXRpb25faWQgEXBob25lX2FwcG9pbnRtZW50BGRhdGUgCjIwMjEtMTAtMDkPYWRkaXRpb25hbF9pbmZvIBhEbGUgdGVsZWZvbmlja8OpIGRvbWx1dnkHbm9uc3RvcBBQBw1yZWxhdGl2ZV9zb3J0OAUMZXhjZXB0aW9uX2lkIAADZGF5IAZzdW5kYXkQc3BlY2lmaWNhdGlvbl9pZCARcGhvbmVfYXBwb2ludG1lbnQEZGF0ZSAKMjAyMS0xMC0xMA9hZGRpdGlvbmFsX2luZm8gGERsZSB0ZWxlZm9uaWNrw6kgZG9tbHV2eQdub25zdG9wEBFpc19wYWlkX29yX25vbmNvbRAJYWRkcl9jaXR5IA9QcmFoYSwgVm9rb3ZpY2UOc29jaWFsX25ldHdvcmtYAA1ncHNfbG9uZ2l0dWRlGAAAAIAasSxACnBob25lc19vYmpYAVADBHJvbGUgBU1vYmlsBm51bWJlciAJNzczMTE4NDcyDGNvdW50cnlfY29kZSADNDIwD2NhdGVnb3J5V2VpZ2h0c1gAEnNob3dfYnVzaW5lc3NfaW5mbxEKZW1haWxzX29ialgBUAIEcm9sZSAGRS1tYWlsBWVtYWlsIA1pbmZvQGlzd2FwLmN6C3VybF92aXNpYmxlIBNodHRwOi8vd3d3Lmlzd2FwLmN6B2lzX3BhaWQQFXRpdGxlX3VzZV9hbHRlcm5hdGl2ZREHZW5hYmxlZBERYWxsb3dlZF90ZW1wbGF0ZXNYASAHZGVmYXVsdAVlbWFpbCAOaXN3YXBAZW1haWwuY3oLZGVzY3JpcHRpb24gWVByb2RlaiBuw6FocmFkbsOtY2ggZMOtbMWvIHBybyBBcHBsZSB6YcWZw616ZW7DrS4gVsO9a3VwIHBvxaFrb3plbsO9Y2ggQXBwbGUgemHFmcOtemVuw60uCGFkZHJfemlwIAUxNjAwMAhzZW9fbmFtZSAXaXN3YXAtY3otcHJhaGEtdm9rb3ZpY2UOY2F0ZWdvcmllc19vYmpYAVAGBG5hbWUgHUVsZWt0cm8sIG1vYmlseSBhIHBvxI3DrXRhxI1lCXBhcmVudF9pZDgACW5hbWVfcGF0aCAdRWxla3RybywgbW9iaWx5IGEgcG/EjcOtdGHEjWUEcGF0aCAZRWxla3Ryby1tb2JpbHktYS1wb2NpdGFjZQdpZF9wYXRoIAMzNzECaWQ5cwELc3ViamVjdF9kaWMgABhvcGVuaW5nX3RpbWVfZGVzY3JpcHRpb24gEk90ZXbDrXJhY8OtIGhvZGlueQN1cmwgVWh0dHA6Ly93d3cuaXN3YXAuY3ojdXRtX3NvdXJjZT1maXJteS5jeiZ1dG1fbWVkaXVtPXBwZCZ1dG1fY2FtcGFpZ249ZmlybXkuY3otMTMwMDE2OTUMZmlsdGVyc19uYW1lWAEgDVBsYXRiYSBrYXJ0b3UIZ3JvdXBfaWQ632PGDmFkZHJfaG91c2VfbnVtIAUyOTkvNA1zdGF0dXNNZXNzYWdlIAJPS1ADBnN0YXR1czjIBnJlc3VsdFAFB3Jldmlld3NYABNyZXZpZXdDb3VudFdlaWdodGVkGAAAAAAAAAAADHRvdGFsTWF0Y2hlczgAEXJldmlld3NQZXJjZW50YWdlGAAAAAAAAPC/DHJldmlld3NTdGFycxgAAAAAAADwvw1zdGF0dXNNZXNzYWdlIAJPSw=="));
*/

</script>

</body>
</html>

