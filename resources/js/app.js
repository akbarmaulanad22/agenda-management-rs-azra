import './bootstrap';

import Alpine from 'alpinejs';
import SignaturePad from 'signature_pad';
import * as XLSX from 'xlsx';

window.Alpine = Alpine;
window.SignaturePad = SignaturePad;
window.XLSX = XLSX;

Alpine.start();
