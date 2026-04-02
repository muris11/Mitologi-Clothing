import 'package:flutter/material.dart';

import '../../config/theme.dart';
import '../../models/address.dart';
import '../../services/address_service.dart';
import '../../utils/responsive_helper.dart';
import '../../widgets/common/mitologi_scaffold.dart';
import '../../widgets/skeleton/skeleton.dart';
import '../../widgets/animations/blur_fade.dart';

class AddressFormScreen extends StatefulWidget {
  final Address? address;

  const AddressFormScreen({super.key, this.address});

  @override
  State<AddressFormScreen> createState() => _AddressFormScreenState();
}

class _AddressFormScreenState extends State<AddressFormScreen> {
  final _formKey = GlobalKey<FormState>();
  final _addressService = AddressService();

  bool _isLoading = false;

  late TextEditingController _labelController;
  late TextEditingController _nameController;
  late TextEditingController _phoneController;
  late TextEditingController _address1Controller;
  late TextEditingController _address2Controller;
  late TextEditingController _cityController;
  late TextEditingController _provinceController;
  late TextEditingController _postalController;
  bool _isPrimary = false;

  @override
  void initState() {
    super.initState();
    final a = widget.address;
    _labelController = TextEditingController(text: a?.label ?? '');
    _nameController = TextEditingController(text: a?.recipientName ?? '');
    _phoneController = TextEditingController(text: a?.phone ?? '');
    _address1Controller = TextEditingController(text: a?.addressLine1 ?? '');
    _address2Controller = TextEditingController(text: a?.addressLine2 ?? '');
    _cityController = TextEditingController(text: a?.city ?? '');
    _provinceController = TextEditingController(text: a?.province ?? '');
    _postalController = TextEditingController(text: a?.postalCode ?? '');
    _isPrimary = a?.isPrimary ?? false;
  }

  @override
  void dispose() {
    _labelController.dispose();
    _nameController.dispose();
    _phoneController.dispose();
    _address1Controller.dispose();
    _address2Controller.dispose();
    _cityController.dispose();
    _provinceController.dispose();
    _postalController.dispose();
    super.dispose();
  }

  Future<void> _saveAddress() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);

    final data = {
      'label': _labelController.text,
      'recipient_name': _nameController.text,
      'phone': _phoneController.text,
      'address_line_1': _address1Controller.text,
      'address_line_2': _address2Controller.text,
      'city': _cityController.text,
      'province': _provinceController.text,
      'postal_code': _postalController.text,
      'is_primary': _isPrimary,
    };

    try {
      if (widget.address == null) {
        await _addressService.addAddress(data);
      } else {
        await _addressService.updateAddress(widget.address!.id, data);
      }
      if (mounted) {
        Navigator.pop(context, true);
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Alamat berhasil disimpan')),
        );
      }
    } catch (e) {
      if (mounted) {
        setState(() => _isLoading = false);
        ScaffoldMessenger.of(
          context,
        ).showSnackBar(SnackBar(content: Text('Gagal menyimpan alamat: $e')));
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return MitologiScaffold(
      title: widget.address == null ? 'Tambah Alamat' : 'Edit Alamat',
      subtitle: 'Lengkapi informasi alamat pengiriman',
      showLogo: false,
      bodyPadding: EdgeInsets.zero,
      body: _isLoading
          ? const AddressFormSkeleton()
          : SingleChildScrollView(
              padding: EdgeInsets.all(
                ResponsiveHelper.horizontalPadding(context),
              ),
              child: FadeInUp(
                child: Form(
                  key: _formKey,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      _buildSectionTitle('Info Kontak'),
                      _buildTextField(
                        controller: _labelController,
                        label: 'Label Alamat (ex: Rumah, Kantor)',
                        validator: (v) =>
                            v!.isEmpty ? 'Label tidak boleh kosong' : null,
                      ),
                      const SizedBox(height: 16),
                      _buildTextField(
                        controller: _nameController,
                        label: 'Nama Penerima',
                        validator: (v) => v!.isEmpty
                            ? 'Nama penerima tidak boleh kosong'
                            : null,
                      ),
                      const SizedBox(height: 16),
                      _buildTextField(
                        controller: _phoneController,
                        label: 'Nomor Telepon',
                        keyboardType: TextInputType.phone,
                        validator: (v) => v!.isEmpty
                            ? 'Nomor telepon tidak boleh kosong'
                            : null,
                      ),

                      const SizedBox(height: 24),
                      _buildSectionTitle('Detail Alamat'),

                      _buildTextField(
                        controller: _provinceController,
                        label: 'Provinsi',
                        validator: (v) =>
                            v!.isEmpty ? 'Provinsi tidak boleh kosong' : null,
                      ),
                      const SizedBox(height: 16),
                      _buildTextField(
                        controller: _cityController,
                        label: 'Kota/Kabupaten',
                        validator: (v) =>
                            v!.isEmpty ? 'Kota tidak boleh kosong' : null,
                      ),
                      const SizedBox(height: 16),
                      _buildTextField(
                        controller: _postalController,
                        label: 'Kode Pos',
                        keyboardType: TextInputType.number,
                        validator: (v) =>
                            v!.isEmpty ? 'Kode pos tidak boleh kosong' : null,
                      ),
                      const SizedBox(height: 16),
                      _buildTextField(
                        controller: _address1Controller,
                        label: 'Alamat Lengkap (Jalan, RT/RW)',
                        maxLines: 3,
                        validator: (v) => v!.isEmpty
                            ? 'Alamat lengkap tidak boleh kosong'
                            : null,
                      ),
                      const SizedBox(height: 16),
                      _buildTextField(
                        controller: _address2Controller,
                        label: 'Detail Tambahan (opsional)',
                        hintText: 'Blok/Unit/Patokan',
                      ),

                      const SizedBox(height: 24),
                      Container(
                        decoration: BoxDecoration(
                          color: AppTheme.surfaceContainerLow,
                          borderRadius: AppTheme.radius16,
                        ),
                        child: SwitchListTile(
                          title: const Text(
                            'Jadikan Alamat Utama',
                            style: TextStyle(
                              fontWeight: FontWeight.w700,
                              fontSize: 15,
                            ),
                          ),
                          subtitle: const Text(
                            'Alamat ini akan dipilih otomatis untuk pengiriman',
                            style: TextStyle(
                              fontSize: 13,
                              color: AppTheme.onSurfaceVariant,
                            ),
                          ),
                          activeTrackColor: AppTheme.primary.withValues(
                            alpha: 0.3,
                          ),
                          activeThumbColor: AppTheme.primary,
                          contentPadding: EdgeInsets.symmetric(
                            horizontal: ResponsiveHelper.horizontalPadding(
                              context,
                            ),
                          ),
                          shape: RoundedRectangleBorder(
                            borderRadius: AppTheme.radius16,
                          ),
                          value: _isPrimary,
                          onChanged: (val) {
                            setState(() => _isPrimary = val);
                          },
                        ),
                      ),

                      const SizedBox(height: 32),
                      SizedBox(
                        width: double.infinity,
                        height: 56,
                        child: ElevatedButton(
                          onPressed: _saveAddress,
                          style: ElevatedButton.styleFrom(
                            backgroundColor: AppTheme.accent,
                            foregroundColor: Colors.white,
                            elevation: 0,
                            shape: RoundedRectangleBorder(
                              borderRadius: AppTheme.radius16,
                            ),
                          ),
                          child: const Text(
                            'Simpan Alamat',
                            style: TextStyle(
                              fontWeight: FontWeight.w700,
                              fontSize: 16,
                            ),
                          ),
                        ),
                      ),
                      const SizedBox(height: 32),
                    ],
                  ),
                ),
              ),
            ),
    );
  }

  Widget _buildSectionTitle(String title) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Text(
        title,
        style: const TextStyle(
          fontWeight: FontWeight.bold,
          fontSize: 16,
          color: AppTheme.primary,
        ),
      ),
    );
  }

  Widget _buildTextField({
    required TextEditingController controller,
    required String label,
    String? hintText,
    int maxLines = 1,
    TextInputType? keyboardType,
    String? Function(String?)? validator,
  }) {
    return TextFormField(
      controller: controller,
      maxLines: maxLines,
      keyboardType: keyboardType,
      validator: validator,
      style: const TextStyle(fontSize: 14),
      decoration: InputDecoration(
        labelText: label,
        hintText: hintText,
        labelStyle: const TextStyle(color: AppTheme.slate500),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: BorderSide.none,
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: BorderSide.none,
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: AppTheme.primary, width: 2),
        ),
        filled: true,
        fillColor: AppTheme.surfaceContainerLow,
      ),
    );
  }
}
