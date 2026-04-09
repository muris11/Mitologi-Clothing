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
    String? asString(dynamic value) => value?.toString();

    // Parse nested general settings
    final general = json['general'] is Map
        ? Map<String, dynamic>.from(json['general'] as Map)
        : null;
    final about = json['about'] is Map
        ? Map<String, dynamic>.from(json['about'] as Map)
        : null;
    final visionMission = json['vision_mission'] is Map
        ? Map<String, dynamic>.from(json['vision_mission'] as Map)
        : null;
    final legalityData = json['legality'] is Map
        ? Map<String, dynamic>.from(json['legality'] as Map)
        : null;
    final servicesData = json['services_data'] ?? json['servicesData'];
    final companyValuesData =
        json['company_values_data'] ?? json['companyValuesData'];

    return SiteSettings(
      // General
      siteName: asString(general?['site_name'] ?? json['siteName']),
      siteTagline: asString(general?['site_tagline'] ?? json['siteTagline']),
      siteDescription: asString(
        general?['site_description'] ?? json['siteDescription'],
      ),
      companyFoundedYear: asString(
        general?['company_founded_year'] ??
            about?['company_founded_year'] ??
            json['companyFoundedYear'],
      ),

      // About
      aboutHeadline: asString(
        about?['about_headline'] ?? json['aboutHeadline'],
      ),
      aboutDescription1: asString(
        about?['about_description_1'] ?? json['aboutDescription1'],
      ),
      aboutDescription2: asString(
        about?['about_description_2'] ?? json['aboutDescription2'],
      ),
      aboutImage: asString(about?['about_image'] ?? json['aboutImage']),
      aboutShortHistory: asString(
        about?['about_short_history'] ?? json['aboutShortHistory'],
      ),
      aboutLogoMeaning: asString(
        about?['about_logo_meaning'] ?? json['aboutLogoMeaning'],
      ),

      // Founder (can be in about or root)
      founderName: asString(
        json['founder_name'] ?? json['founderName'] ?? about?['founder_name'],
      ),
      founderRole: asString(
        json['founder_role'] ?? json['founderRole'] ?? about?['founder_role'],
      ),
      founderStory: asString(
        json['founder_story'] ??
            json['founderStory'] ??
            about?['founder_story'],
      ),
      founderPhoto: asString(
        json['founder_photo'] ??
            json['founderPhoto'] ??
            about?['founder_photo'],
      ),

      // Vision & Mission
      visionText: asString(
        visionMission?['vision_text'] ??
            about?['vision_text'] ??
            json['visionText'],
      ),
      missionText: asString(
        visionMission?['mission_text'] ??
            about?['mission_text'] ??
            json['missionText'],
      ),
      valuesText: asString(
        visionMission?['values_text'] ??
            about?['values_text'] ??
            json['valuesText'],
      ),

      // Contact
      whatsappNumber: asString(
        json['whatsapp_number'] ?? json['whatsappNumber'] ?? json['wa_number'],
      ),

      // Data arrays
      servicesData: (servicesData as List?)
          ?.map(
            (s) => ServiceData.fromJson(Map<String, dynamic>.from(s as Map)),
          )
          .toList(),
      companyValues: (companyValuesData as List?)
          ?.map(
            (v) => CompanyValue.fromJson(Map<String, dynamic>.from(v as Map)),
          )
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
      title: json['title']?.toString() ?? '',
      desc: json['desc']?.toString() ?? '',
      image: json['image']?.toString() ?? '',
      materials: json['materials']?.toString(),
      keunggulan: json['keunggulan']?.toString(),
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
      title: json['title']?.toString() ?? '',
      description: (json['desc'] ?? json['description'])?.toString() ?? '',
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
      legalCompanyName: json['legal_company_name']?.toString(),
      legalAddress: json['legal_address']?.toString(),
      legalBusinessField: json['legal_business_field']?.toString(),
      legalNpwp: json['legal_npwp']?.toString(),
      legalNib: json['legal_nib']?.toString(),
      legalNmid: json['legal_nmid']?.toString(),
    );
  }
}
