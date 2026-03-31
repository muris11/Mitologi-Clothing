class SiteSettings {
  // General
  final String? siteName;
  final String? siteTagline;
  final String? siteDescription;
  final String? companyFoundedYear;

  // About
  final String? aboutHeadline;
  final String? aboutDescription1;
  final String? aboutDescription2;
  final String? aboutImage;
  final String? aboutShortHistory;
  final String? aboutLogoMeaning;

  // Founder
  final String? founderName;
  final String? founderRole;
  final String? founderStory;
  final String? founderPhoto;

  // Vision & Mission
  final String? visionText;
  final String? missionText;
  final String? valuesText;

  // Contact
  final String? whatsappNumber;

  // Data arrays
  final List<ServiceData>? servicesData;
  final List<CompanyValue>? companyValues;
  final LegalityInfo? legality;

  SiteSettings({
    this.siteName,
    this.siteTagline,
    this.siteDescription,
    this.companyFoundedYear,
    this.aboutHeadline,
    this.aboutDescription1,
    this.aboutDescription2,
    this.aboutImage,
    this.aboutShortHistory,
    this.aboutLogoMeaning,
    this.founderName,
    this.founderRole,
    this.founderStory,
    this.founderPhoto,
    this.visionText,
    this.missionText,
    this.valuesText,
    this.whatsappNumber,
    this.servicesData,
    this.companyValues,
    this.legality,
  });

  factory SiteSettings.fromJson(Map<String, dynamic> json) {
    // Parse nested general settings
    final general = json['general'] as Map<String, dynamic>?;
    final about = json['about'] as Map<String, dynamic>?;
    final visionMission = json['vision_mission'] as Map<String, dynamic>?;
    final legalityData = json['legality'] as Map<String, dynamic>?;

    return SiteSettings(
      // General
      siteName: general?['site_name'],
      siteTagline: general?['site_tagline'],
      siteDescription: general?['site_description'],
      companyFoundedYear:
          general?['company_founded_year'] ?? about?['company_founded_year'],

      // About
      aboutHeadline: about?['about_headline'],
      aboutDescription1: about?['about_description_1'],
      aboutDescription2: about?['about_description_2'],
      aboutImage: about?['about_image'],
      aboutShortHistory: about?['about_short_history'],
      aboutLogoMeaning: about?['about_logo_meaning'],

      // Founder (can be in about or root)
      founderName: json['founder_name'] ?? about?['founder_name'],
      founderRole: json['founder_role'] ?? about?['founder_role'],
      founderStory: json['founder_story'] ?? about?['founder_story'],
      founderPhoto: json['founder_photo'] ?? about?['founder_photo'],

      // Vision & Mission
      visionText: visionMission?['vision_text'] ?? about?['vision_text'],
      missionText: visionMission?['mission_text'] ?? about?['mission_text'],
      valuesText: visionMission?['values_text'] ?? about?['values_text'],

      // Contact
      whatsappNumber: json['whatsapp_number'] ?? json['wa_number'],

      // Data arrays
      servicesData: (json['services_data'] as List?)
          ?.map((s) => ServiceData.fromJson(s))
          .toList(),
      companyValues: (json['company_values_data'] as List?)
          ?.map((v) => CompanyValue.fromJson(v))
          .toList(),
      legality: legalityData != null
          ? LegalityInfo.fromJson(legalityData)
          : null,
    );
  }
}

class ServiceData {
  final String title;
  final String desc;
  final String image;
  final String? materials;
  final String? keunggulan;

  ServiceData({
    required this.title,
    required this.desc,
    required this.image,
    this.materials,
    this.keunggulan,
  });

  factory ServiceData.fromJson(Map<String, dynamic> json) {
    return ServiceData(
      title: json['title'] ?? '',
      desc: json['desc'] ?? '',
      image: json['image'] ?? '',
      materials: json['materials'],
      keunggulan: json['keunggulan'],
    );
  }

  List<String> get materialsList =>
      materials
          ?.split(',')
          .map((m) => m.trim())
          .where((m) => m.isNotEmpty)
          .toList() ??
      [];

  List<String> get keunggulanList =>
      keunggulan
          ?.split(',')
          .map((k) => k.trim())
          .where((k) => k.isNotEmpty)
          .toList() ??
      [];
}

class CompanyValue {
  final String title;
  final String description;

  CompanyValue({required this.title, required this.description});

  factory CompanyValue.fromJson(Map<String, dynamic> json) {
    return CompanyValue(
      title: json['title'] ?? '',
      description: json['desc'] ?? json['description'] ?? '',
    );
  }
}

class LegalityInfo {
  final String? legalCompanyName;
  final String? legalAddress;
  final String? legalBusinessField;
  final String? legalNpwp;
  final String? legalNib;
  final String? legalNmid;

  LegalityInfo({
    this.legalCompanyName,
    this.legalAddress,
    this.legalBusinessField,
    this.legalNpwp,
    this.legalNib,
    this.legalNmid,
  });

  factory LegalityInfo.fromJson(Map<String, dynamic> json) {
    return LegalityInfo(
      legalCompanyName: json['legal_company_name'],
      legalAddress: json['legal_address'],
      legalBusinessField: json['legal_business_field'],
      legalNpwp: json['legal_npwp'],
      legalNib: json['legal_nib'],
      legalNmid: json['legal_nmid'],
    );
  }
}
