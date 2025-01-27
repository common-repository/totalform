var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __generator = (this && this.__generator) || function (thisArg, body) {
    var _ = { label: 0, sent: function() { if (t[0] & 1) throw t[1]; return t[1]; }, trys: [], ops: [] }, f, y, t, g;
    return g = { next: verb(0), "throw": verb(1), "return": verb(2) }, typeof Symbol === "function" && (g[Symbol.iterator] = function() { return this; }), g;
    function verb(n) { return function (v) { return step([n, v]); }; }
    function step(op) {
        if (f) throw new TypeError("Generator is already executing.");
        while (_) try {
            if (f = 1, y && (t = op[0] & 2 ? y["return"] : op[0] ? y["throw"] || ((t = y["return"]) && t.call(y), 0) : y.next) && !(t = t.call(y, op[1])).done) return t;
            if (y = 0, t) op = [op[0] & 2, t.value];
            switch (op[0]) {
                case 0: case 1: t = op; break;
                case 4: _.label++; return { value: op[1], done: false };
                case 5: _.label++; y = op[1]; op = [0]; continue;
                case 7: op = _.ops.pop(); _.trys.pop(); continue;
                default:
                    if (!(t = _.trys, t = t.length > 0 && t[t.length - 1]) && (op[0] === 6 || op[0] === 2)) { _ = 0; continue; }
                    if (op[0] === 3 && (!t || (op[1] > t[0] && op[1] < t[3]))) { _.label = op[1]; break; }
                    if (op[0] === 6 && _.label < t[1]) { _.label = t[1]; t = op; break; }
                    if (t && _.label < t[2]) { _.label = t[2]; _.ops.push(op); break; }
                    if (t[2]) _.ops.pop();
                    _.trys.pop(); continue;
            }
            op = body.call(thisArg, _);
        } catch (e) { op = [6, e]; y = 0; } finally { f = t = 0; }
        if (op[0] & 5) throw op[1]; return { value: op[0] ? op[1] : void 0, done: true };
    }
};
function TotalForm(props) {
    return {
        isProcessing: false,
        form: props.form,
        config: props.config,
        errors: {
            blocks: {},
            global: []
        },
        mounted: function () {
            postHeight();
        },
        validateSection: function (data) {
            return __awaiter(this, void 0, void 0, function () {
                return __generator(this, function (_a) {
                    return [2, jQuery.ajax({
                            url: "".concat(this.config.apiBase, "/section"),
                            headers: this.config.nonce ? { 'X-WP-Nonce': this.config.nonce } : {},
                            type: 'post',
                            processData: false,
                            contentType: false,
                            enctype: 'multipart/form-data',
                            data: data,
                        })];
                });
            });
        },
        submitEntry: function (data) {
            if (data === void 0) { data = this.getEntryDataAsFormData(); }
            return __awaiter(this, void 0, void 0, function () {
                var _a, _b, _c;
                return __generator(this, function (_d) {
                    switch (_d.label) {
                        case 0:
                            if (!this.form.settings.behaviors.recaptcha.enabled) return [3, 2];
                            _b = (_a = data).set;
                            _c = ['recaptcha'];
                            return [4, grecaptcha.execute("".concat(this.config.recaptcha.siteKey), { action: 'submit' })];
                        case 1:
                            _b.apply(_a, _c.concat([_d.sent()]));
                            _d.label = 2;
                        case 2: return [2, jQuery.ajax({
                                url: "".concat(this.config.apiBase, "/entry"),
                                headers: this.config.nonce ? { 'X-WP-Nonce': this.config.nonce } : {},
                                type: 'post',
                                processData: false,
                                contentType: false,
                                enctype: 'multipart/form-data',
                                data: data,
                                dataType: 'json'
                            })];
                    }
                });
            });
        },
        submit: function () {
            var _a, _b;
            return __awaiter(this, void 0, void 0, function () {
                var formData, response, submission, e_1, errors, blockUid;
                var _c;
                return __generator(this, function (_d) {
                    switch (_d.label) {
                        case 0:
                            if (this.form.preview) {
                                this.$refs.form.replaceWith(this.$refs.thankyou.content);
                                return [2];
                            }
                            this.isProcessing = true;
                            formData = new FormData(this.$refs.form);
                            _d.label = 1;
                        case 1:
                            _d.trys.push([1, 5, , 6]);
                            return [4, this.validateSection(formData)];
                        case 2:
                            response = _d.sent();
                            if (!(response.data.action === 'submit')) return [3, 4];
                            return [4, this.submitEntry(formData)];
                        case 3:
                            submission = _d.sent();
                            if (submission.data.customView) {
                                jQuery(this.$refs.form).html(submission.data.customView);
                            }
                            _d.label = 4;
                        case 4: return [3, 6];
                        case 5:
                            e_1 = _d.sent();
                            errors = (e_1.responseJSON || JSON.parse(e_1.responseText || '{}')).data || {};
                            this.errors.blocks = {};
                            this.errors.global = [((_a = e_1.responseJSON) === null || _a === void 0 ? void 0 : _a.message) || ((_b = e_1.responseJSON) === null || _b === void 0 ? void 0 : _b.error) || e_1.message];
                            for (blockUid in errors) {
                                if (this.$refs[blockUid]) {
                                    this.errors.blocks[blockUid] = errors[blockUid][0] || null;
                                }
                                else {
                                    (_c = this.errors.global).push.apply(_c, errors[blockUid]);
                                }
                            }
                            this.$refs.form.scrollIntoView({ behavior: 'smooth' });
                            return [3, 6];
                        case 6:
                            postHeight();
                            this.isProcessing = false;
                            return [2];
                    }
                });
            });
        }
    };
}
var selectOther = function (ctx) {
    ctx.el.addEventListener('change', function (event) {
        if (ctx.arg === event.target.value) {
            document.getElementById(ctx.exp).classList.add('select-other');
        }
        else {
            document.getElementById(ctx.exp).classList.remove('select-other');
        }
    });
    return function () {
    };
};
document.querySelectorAll('.totalform')
    .forEach(function (totalform) {
    PetiteVue.createApp({ TotalForm: TotalForm }).directive('select', selectOther).mount(totalform);
});
var postHeight = function () {
    setTimeout(function () {
        top.postMessage({
            totalform: {
                action: 'resizeHeight',
                value: document.documentElement.scrollHeight
            }
        }, '*');
    }, 100);
};
window.addEventListener("message", function (event) { return event.data.totalform && event.data.totalform.action === 'requestHeight' && postHeight(); }, false);
//# sourceMappingURL=app.js.map